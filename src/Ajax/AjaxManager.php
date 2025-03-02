<?php
declare(strict_types=1);

namespace OnePix\WordPressComponents\Ajax;

use Closure;
use Exception;
use OnePix\WordPressComponents\TypedArray\TypedArray;
use OnePix\WordPressContracts\ActionsRegistrar;

/**
 * ToDo: Refactor and split into classes
 */
abstract class AjaxManager {
	/**
	 * Prefix for actions
	 *
	 * @var string
	 */
    public const PREFIX = '';

	/**
	 * List of action methods in children class
	 *
	 * @var list<non-empty-string>
	 */
	public const ACTIONS = [];

	protected TypedArray $get;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
	protected TypedArray $post;

    /**
     * @param  Closure(callable):mixed  $runAction  function from di container to autowire dependencies
     */
    private readonly Closure $runAction;

    public function __construct(
        private readonly string $appPrefix,
        private readonly ActionsRegistrar $actionsRegistrar,
        null|callable $runAction = null,
    ) {
        $this->runAction = $runAction === null ?
            fn(Closure $callback): mixed => call_user_func($callback) : $runAction(...);
    }

    public function register(): void {
        foreach (static::ACTIONS as $action) {
            $this->actionsRegistrar->add('wp_ajax_' . $this->getActionName($action), $this->preAction(...), 10, 0);
            $this->actionsRegistrar->add('wp_ajax_nopriv_' . $this->getActionName($action), $this->preAction(...), 10, 0);

            $this->actionsRegistrar->add('wp_ajax_' . $this->getActionName($action), fn() => $this->action($action), 100, 0);
            $this->actionsRegistrar->add('wp_ajax_nopriv_' . $this->getActionName($action), fn() => $this->action($action), 100, 0);
        }
    }

	/**
     * Return array of action urls
     *
     * @param  bool $addNonce add nonce to url.
     */
    public function getActionUrls( bool $addNonce = true ): array {
		$actions = [];

		foreach (static::ACTIONS as $action) {
			$actions[ $action ] = $this->getActionUrl($action, [], $addNonce);
		}

		return $actions;
	}

	/**
     * Returns ajax url by short action name
     *
     * @param string $shortName      short action name.
     * @param array  $additionalArgs additional url arguments.
     * @param bool   $addNonce       add nonce to url.
     */
    public function getActionUrl( string $shortName, array $additionalArgs = [], bool $addNonce = true ): string
    {
		$action = $this->getActionName($shortName);

		$requestUrl = add_query_arg('action', $action, admin_url('admin-ajax.php'));

		if ($additionalArgs !== []) {
			$requestUrl = add_query_arg(
				$additionalArgs,
				$requestUrl
			);
		}

		if ($addNonce) {
            return add_query_arg(
				[
					'_wpnonce' => wp_create_nonce($this->getActionName($shortName)),
				],
				$requestUrl
			);
        }

		return $requestUrl;
	}

	/**
     * Returns ajax action name by short action name
     *
     * @param  string $shortName  short action name.
     *
     *
     * @throws AjaxActionNotFoundException
     */
    public function getActionName( string $shortName ): string
    {
		if (in_array($shortName, static::ACTIONS, true)) {
			return $this->appPrefix . '_' . static::PREFIX . '_' . $shortName;
		}

        throw AjaxActionNotFoundException::byAction($shortName, $this);
	}

	/**
     * Verify nonce in $_GET array
     *
     * @param  string $functionName  function (action) name to verify. Use __FUNCTION__ to get right function name.
     * @param  bool   $mustBeLoggedIn must the user be logged in.
     */
    protected function verifyNonce( string $functionName = '', bool $mustBeLoggedIn = false ): void {
		if ($mustBeLoggedIn && ! is_user_logged_in()) {
			wp_die(esc_html__('You must be logged in', 'dashamail-exporter'));
		}

        $nonce = sanitize_text_field(wp_unslash($this->get->getString('_wpnonce', '')));

		$verified = wp_verify_nonce($nonce, $this->getActionName($functionName) ?: '');

		if ($verified === false) {
			wp_die(esc_html__('Action failed. Please try again.', 'dashamail-exporter'));
		}
	}

	/**
	 * Action firing before main action
	 */
    private function preAction(): void {
		try {
			$this->get = new TypedArray($_GET);

			/**
			 * If request content type is application/json and $_POST is empty decode data and put it to $_POST
			 */
			if ($_POST === [] && isset($_SERVER['CONTENT_TYPE']) && 'application/json' === $_SERVER['CONTENT_TYPE']) {
				$this->post = json_decode(file_get_contents('php://input'), true);
			} else {
				$this->post = new TypedArray($_POST);
			}
		} catch (Exception) {
			wp_send_json_error();
		}
	}

	/**
     * @param  non-empty-string  $action
     */
    private function action( string $action ): never {
		try {
			if (! method_exists($this, $action)) {
				throw new \Exception(sprintf(
					'Action handler for %s action not found in %s',
					$action,
					static::class
				));
			}

            call_user_func($this->runAction, [ $this, $action ]);
		} catch (\Exception $e) {
			wp_send_json_error($e->getMessage());
		}

		wp_send_json_error(sprintf("Ajax handler must be of type never. %s::%s isn't never type", static::class, $action));
	}
}
