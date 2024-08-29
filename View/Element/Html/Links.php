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

namespace ViraXpress\Framework\View\Element\Html;

use Magento\Framework\View\Element\Html\Links as MagentoLinks;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ViraXpress\Configuration\Helper\Data;

class Links extends \Magento\Framework\View\Element\Html\Links
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
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

        $html = '';
        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if ($enableViraXpress && $isViraXpressTheme && $this->getLinks() && $this->getTitle()) {
            return $this->getViraXpressHtml();
        }

        return $this->getDefaultHtml();
    }

    /**
     * Get HTML for ViraXpress theme
     *
     * @return string
     */
    private function getViraXpressHtml(): string
    {
        $html = '<div><p class="font-semibold leading-6 text-gray-800">' . $this->getTitle() . '</p>';
        $html .= '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml($this->getCssClass()) . '"' : '') . '>';

        foreach ($this->getLinks() as $link) {
            $html .= $this->renderLink($link);
        }

        $html .= '</ul></div>';

        return $html;
    }

    /**
     * Get default HTML
     *
     * @return string
     */
    private function getDefaultHtml(): string
    {
        $html = '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml($this->getCssClass()) . '"' : '') . '>';

        foreach ($this->getLinks() as $link) {
            $html .= $this->renderLink($link);
        }

        $html .= '</ul>';

        return $html;
    }
}
