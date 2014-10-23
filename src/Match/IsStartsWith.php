<?php
/**
 * This file is part of the Ray.Aop package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\Aop\Match;

use Ray\Aop\MatchInterface;

class IsStartsWith implements MatchInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($name, $target, array $args)
    {
        unset($target);
        list($startsWith) = $args;
        if ($name instanceof \ReflectionMethod) {
            $name = $name->name;
        }
        $result = (strpos($name, $startsWith) === 0) ? true : false;

        return $result;
    }
}