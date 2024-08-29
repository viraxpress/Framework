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

use Magento\Framework\View\Element\Html\Date as MagentoDate;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ViraXpress\Configuration\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

/**
 * Date element block
 */
class Date extends MagentoDate
{

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
     * @param LayoutFactory $layoutFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory,
        ScopeConfigInterface $scopeConfig,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->layoutFactory = $layoutFactory;
        $this->scopeConfig = $scopeConfig;
        $this->helperData = $helperData;
    }

    /**
     * Render block HTML
     *
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _toHtml()
    {
        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if ($enableViraXpress && $isViraXpressTheme) {
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
        $dateHtml = $this->layoutFactory->create()->createBlock(\Magento\Framework\View\Element\Template::class)
            ->setTemplate('Magento_Theme::html/fields/dob.phtml')
            ->setName($this->getName())
            ->setId($this->getId())
            ->setValue($this->getValue())
            ->setClass($this->getClass())
            ->setExtraParams($this->getExtraParams())
            ->setYearsRange($this->getYearsRange())
            ->setChangeMonth($this->getChangeMonth())
            ->setChangeYear($this->getChangeYear())
            ->setMaxDate($this->getMaxDate())
            ->setShowOn($this->getShowOn())
            ->setFirstDay($this->getFirstDay());

        return $dateHtml->toHtml();
    }

    /**
     * Get default HTML
     *
     * @return string
     */
    private function getDefaultHtml(): string
    {
        $html = '<input type="text" name="' . $this->getName() . '" id="' . $this->getId() . '" ';
        $html .= 'value="' . $this->escapeHtml($this->getValue()) . '" ';
        $html .= 'class="' . $this->getClass() . '" ' . $this->getExtraParams() . '/> ';

        $calendarYearsRange = $this->getYearsRange();
        $changeMonth = $this->getChangeMonth();
        $changeYear = $this->getChangeYear();
        $maxDate = $this->getMaxDate();
        $showOn = $this->getShowOn();
        $firstDay = $this->getFirstDay();

        $html .= '<script type="text/javascript">
            require(["jquery", "mage/calendar"], function($){
                $("#' . $this->getId() . '").calendar({
                    showsTime: ' . ($this->getTimeFormat() ? 'true' : 'false') . ',
                    ' . ($this->getTimeFormat() ? 'timeFormat: "' . $this->getTimeFormat() . '",' : '') . '
                    dateFormat: "' . $this->getDateFormat() . '",
                    buttonImage: "' . $this->getImage() . '",
                    ' . ($calendarYearsRange ? 'yearRange: "' . $calendarYearsRange . '",' : '') . '
                    buttonText: "' . __('Select Date') . '"
                    ' . ($maxDate ? ', maxDate: "' . $maxDate . '"' : '') . '
                    ' . ($changeMonth !== null ? ', changeMonth: ' . $changeMonth : '') . '
                    ' . ($changeYear !== null ? ', changeYear: ' . $changeYear : '') . '
                    ' . ($showOn ? ', showOn: "' . $showOn . '"' : '') . '
                    ' . ($firstDay ? ', firstDay: ' . $firstDay : '') . '
                });
            });
        </script>';

        return $html;
    }
}
