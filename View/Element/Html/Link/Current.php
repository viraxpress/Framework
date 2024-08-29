<?php
/**
 * ViraXpress - https://www.viraxpress.com
 *
 * LICENSE AGREEMENT
 *
 * This file is part of the ViraXpress package and is licensed under the ViraXpress license agreement.
 * You can view the full license at:
 * https://www.viraxpress.com/license
 *
 * By utilizing this file, you agree to comply with the terms outlined in the ViraXpress license.
 *
 * DISCLAIMER
 *
 * Modifications to this file are discouraged to ensure seamless upgrades and compatibility with future releases.
 *
 * @category    ViraXpress
 * @package     ViraXpress_Framework
 * @author      ViraXpress
 * @copyright   © 2024 ViraXpress (https://www.viraxpress.com/)
 * @license     https://www.viraxpress.com/license
 */

namespace ViraXpress\Framework\View\Element\Html\Link;

use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ViraXpress\Configuration\Helper\Data;

/**
 * Block representing link with two possible states.
 * "Current" state means link leads to URL equivalent to URL of currently displayed page.
 *
 */
class Current extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * Search redundant /index and / in url
     */
    private const REGEX_INDEX_URL_PATTERN = '/(\/index|(\/))+($|\/$)/';

    /**
     * @var DefaultPathInterface
     */
    protected $_defaultPath;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param DefaultPathInterface $defaultPath
     * @param LayoutFactory $layoutFactory
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        DefaultPathInterface $defaultPath,
        LayoutFactory $layoutFactory,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->scopeConfig = $scopeConfig;
        $this->layoutFactory = $layoutFactory;
        $this->helperData = $helperData;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if ($enableViraXpress && $isViraXpressTheme) {
            return $this->getViraXpressThemeHtml();
        }

        return $this->getDefaultHtml();
    }

    /**
     * Get HTML for ViraXpress theme
     *
     * @return string
     */
    private function getViraXpressThemeHtml(): string
    {
        $footerLinks = $this->layoutFactory->create()->createBlock(\Magento\Framework\View\Element\Template::class)
            ->setTemplate('Magento_Theme::html/footer/links.phtml')
            ->setIsHighlighted($this->getIsHighlighted())
            ->setIsCurrent($this->isCurrent())
            ->setLabel($this->getLabel())
            ->setTitle($this->getTitle())
            ->setAttributesHtml($this->getAttributesHtml())
            ->setHref($this->getHref());

        return $footerLinks->toHtml();
    }

    /**
     * Get default HTML
     *
     * @return string
     */
    private function getDefaultHtml(): string
    {
        $highlight = $this->getIsHighlighted() ? ' current' : '';

        if ($this->isCurrent()) {
            return '<li class="nav item current"><strong>' . $this->escapeHtml(__($this->getLabel())) . '</strong></li>';
        }

        $html = '<li class="nav item' . $highlight . '"><a href="' . $this->escapeHtml($this->getHref()) . '"';
        $html .= $this->getTitle() ? ' title="' . $this->escapeHtml(__($this->getTitle())) . '"' : '';
        $html .= $this->getAttributesHtml() . '>';
        if ($this->getIsHighlighted()) {
            $html .= '<strong>';
        }
        $html .= $this->escapeHtml(__($this->getLabel()));
        if ($this->getIsHighlighted()) {
            $html .= '</strong>';
        }
        $html .= '</a></li>';

        return $html;
    }
}
