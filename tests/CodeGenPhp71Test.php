<?php

declare(strict_types=1);

namespace Ray\Aop;

use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

class CodeGenPhp71Test extends TestCase
{
    /**
     * @var CodeGen
     */
    private $codeGen;

    protected function setUp() : void
    {
        $this->codeGen = new CodeGen((new ParserFactory)->newInstance(), new BuilderFactory, new Standard);
    }

    public function testReturnTypeVoid()
    {
        $bind = new Bind;
        $bind->bindInterceptors('returnTypeVoid', []);
        $code = $this->codeGen->generate('a', new \ReflectionClass(FakePhp71NullableClass::class), $bind);
        $expected = 'function returnTypeVoid() : void';
        $this->assertContains($expected, $code);
    }

    public function testReturnTypeNullable()
    {
        $bind = new Bind;
        $bind->bindInterceptors('returnNullable', []);
        $code = $this->codeGen->generate('a', new \ReflectionClass(FakePhp71NullableClass::class), $bind);
        $expected = 'function returnNullable(string $str) : ?';
        $this->assertContains($expected, $code);

        return $code;
    }

    /**
     * @depends testReturnTypeNullable
     */
    public function testContainsStatement(string $code)
    {
        $this->assertContains("declare (strict_types=1);\n", $code);
        $this->assertContains("use Composer\\Autoload;\n", $code);
    }

    public function testNullableParam()
    {
        $bind = new Bind;
        $bind->bindInterceptors('nullableParam', []);
        $code = $this->codeGen->generate('a', new \ReflectionClass(FakePhp71NullableClass::class), $bind);
        $expected = 'function nullableParam(?int $id, string $name = null)';
        $this->assertContains($expected, $code);
    }

    public function testTypedParam()
    {
        $bind = new Bind;
        $bind->bindInterceptors('typed', []);
        $code = $this->codeGen->generate('a', new \ReflectionClass(FakePhp71NullableClass::class), $bind);
        $expected = <<<'EOT'
    function typed(\SplObjectStorage $storage)
EOT;
        $this->assertContains($expected, $code);
    }

    public function testUseTyped()
    {
        $bind = new Bind;
        $bind->bindInterceptors('useTyped', []);
        $code = $this->codeGen->generate('a', new \ReflectionClass(FakePhp71NullableClass::class), $bind);
        $expected = <<<'EOT'
    function useTyped(\Ray\Aop\CodeGen $codeGen)
EOT;
        $this->assertContains($expected, $code);
    }
}
