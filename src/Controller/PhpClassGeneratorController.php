<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract, ParserFactory, Error, NodeDumper};

class PhpClassGeneratorController extends AbstractController
{

    #[Route('/generator', name: 'app_php_class_generator')]
    public function index(): Response
    {

        $reflectionClass = new \ReflectionClass(Event::class);
        dd($reflectionClass, $reflectionClass->getProperties());

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new class extends NodeVisitorAbstract {
            public function leaveNode(Node $node) {
                dump($node->getAttributes(), $node->getType());
                if ($node instanceof Node\Scalar\LNumber) {
                    return new Node\Scalar\String_((string) $node->value);
                }
                dd($node->getType());
            }
        });

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
            $ast = $parser->parse(file_get_contents($fn = __DIR__ . '/../Entity/Event.php'));
        try {
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";

        }

        $dumper = new NodeDumper;
        $astString =  $dumper->dump($ast) . "\n";

$modifiedStmts = $traverser->traverse($ast);
        dd($ast);


        return $this->render('php_class_generator/index.html.twig', [
            'controller_name' => 'PhpClassGeneratorController',
        ]);
    }
}
