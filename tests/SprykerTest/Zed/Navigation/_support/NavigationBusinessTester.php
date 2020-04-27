<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation;

use Codeception\Actor;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\Navigation\Business\NavigationFacadeInterface getFacade()
 */
class NavigationBusinessTester extends Actor
{
    use _generated\NavigationBusinessTesterActions;

    /**
     * @param string $name
     * @param string $key
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function createNavigation(string $name, string $key, bool $isActive): NavigationTransfer
    {
        $baseNavigationEntity = $this->createNavigationEntity($name, $key, $isActive);

        return (new NavigationTransfer())
            ->setKey($baseNavigationEntity->getKey())
            ->setName($baseNavigationEntity->getName())
            ->setIdNavigation($baseNavigationEntity->getIdNavigation());
    }

    /**
     * @param int $idNavigation
     * @param int|null $idParentNavigationNode
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function createNavigationNode(int $idNavigation, ?int $idParentNavigationNode = null): NavigationNodeTransfer
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer
            ->setFkParentNavigationNode($idParentNavigationNode)
            ->setFkNavigation($idNavigation)
            ->setIsActive(true);
        $idLocale1 = $this->haveLocale(['localeName' => 'ab_CD'])->getIdLocale();
        $navigationNodeLocalizedAttributesTransfer1 = $this->createNavigationNodeLocalizedAttributesTransfer(
            $idLocale1,
            'Node 1',
            'http://example.com/ab/1'
        );
        $idLocale2 = $this->haveLocale(['localeName' => 'ef_GH'])->getIdLocale();
        $navigationNodeLocalizedAttributesTransfer2 = $this->createNavigationNodeLocalizedAttributesTransfer(
            $idLocale2,
            'Node 1',
            'http://example.com/ef/1'
        );
        $navigationNodeTransfer
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer1)
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer2);

        return $this->getFacade()->createNavigationNode($navigationNodeTransfer);
    }

    /**
     * @param int $idLocale
     * @param string $nodeTitle
     * @param string $externalUrl
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function createNavigationNodeLocalizedAttributesTransfer(
        int $idLocale,
        string $nodeTitle,
        string $externalUrl
    ): NavigationNodeLocalizedAttributesTransfer {
        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer
            ->setFkLocale($idLocale)
            ->setTitle($nodeTitle)
            ->setExternalUrl($externalUrl);

        return $navigationNodeLocalizedAttributesTransfer;
    }

    /**
     * @param string $key
     * @param string $name
     * @param bool $isActive
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation
     */
    public function createNavigationEntity(string $key, string $name, bool $isActive): SpyNavigation
    {
        $navigationEntity = new SpyNavigation();
        $navigationEntity
            ->setKey($key)
            ->setName($name)
            ->setIsActive($isActive)
            ->save();

        return $navigationEntity;
    }
}
