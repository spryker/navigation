<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Navigation\Twig;

use Spryker\Client\Navigation\NavigationClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Twig\Environment;
use Twig\TwigFunction;

class NavigationTwigExtension extends TwigExtension
{
    /**
     * @var string
     */
    public const EXTENSION_NAME = 'NavigationTwigExtension';

    /**
     * @var string
     */
    public const FUNCTION_NAME_NAVIGATION = 'spyNavigation';

    /**
     * @var \Spryker\Client\Navigation\NavigationClientInterface
     */
    protected $navigationClient;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var array
     */
    protected static $buffer = [];

    /**
     * @param \Spryker\Client\Navigation\NavigationClientInterface $navigationClient
     * @param string $locale
     */
    public function __construct(NavigationClientInterface $navigationClient, string $locale)
    {
        $this->navigationClient = $navigationClient;
        $this->locale = $locale;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(static::FUNCTION_NAME_NAVIGATION, [$this, 'renderNavigation'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $navigationKey
     * @param string $template
     *
     * @return string
     */
    public function renderNavigation(Environment $twig, $navigationKey, $template)
    {
        $key = $navigationKey . '-' . $this->locale;

        if (!isset(static::$buffer[$key])) {
            $navigationTreeTransfer = $this->navigationClient->findNavigationTreeByKey($navigationKey, $this->locale);

            static::$buffer[$key] = $navigationTreeTransfer;
        }

        /** @var \Generated\Shared\Transfer\NavigationTreeTransfer|null $navigationTreeTransfer */
        $navigationTreeTransfer = static::$buffer[$key];

        if (!$navigationTreeTransfer || !$navigationTreeTransfer->getNavigation()->getIsActive()) {
            return '';
        }

        return $twig->render($template, [
            'navigationTree' => $navigationTreeTransfer,
        ]);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getLocale()
    {
        return $this->locale;
    }
}
