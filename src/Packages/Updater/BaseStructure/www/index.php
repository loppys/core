<?php

use Vengine\App;

require_once('../vendor/autoload.php');

try {
    (new App())->run();
} catch (ReflectionException $e) {
} catch (\Vengine\System\Exceptions\AccessDeniedException $e) {
} catch (\Vengine\System\Exceptions\MethodNotAllowedException $e) {
} catch (\Vengine\System\Exceptions\PageNotFoundException $e) {
} catch (\Vengine\System\Exceptions\AppException $e) {
}
