<?php

namespace App\Menu;

use App\Entity\Bill;
use App\Entity\Scorecard;
use App\Repository\BillRepository;
use App\Repository\JurisdictionRepository;
use Survos\BaseBundle\Menu\AdminMenuTrait;
use Survos\WorkflowBundle\Service\WorkflowHelperService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\Builder\MenuItemBuilder;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;
use function Symfony\Component\String\u;

class AdminMenu extends BaseAdminMenu
{
    use AdminMenuTrait;

    public function __construct(private AuthorizationCheckerInterface $security,
                                protected Environment $twig,
                                protected UmbrellaAdminConfiguration $configuration,
                                RequestStack $requestStack)
    {
        parent::__construct($this->twig, $configuration);
    }


    public function buildMenu(MenuBuilder $builder, array $options)
    {
        $u = $builder->root();
        $this->addMenuItem($u, ['route' => 'app_test']);

        // _docs/html/

//        $u = $r->add('umbrella');

        $u->add('about')
            ->icon('mdi mdi-lifebuoy')
            ->route('app_homepage');


        $formMenu = $u->add('extras')
            ->icon('uil-document-layout-center');

        $formMenu
            ->add('basic')
            ->route('app_homepage')
            ->end();

        $formMenu
            ->add('blank')
            ->route('app_blank_page')
            ->end();

        $menu = $u;

        $this->addMenuItem($menu, ['route' => 'app_homepage', 'label' => "Home", 'icon' => 'fas fa-home']);
        $this->addMenuItem($menu, ['route' => 'app_foothills', 'label' => "Foothills", 'icon' => 'fas fa-newspaper']);
    }

}
