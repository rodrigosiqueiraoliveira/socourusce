/**
 * Madia Adyen Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Madia
 * @package	Madia_Adyen
 * @copyright	Copyright (c) 2011 Madia (http://www.madia.nl)
 * @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @category   Payment Gateway
 * @package    Madia_Adyen
 * @author     Omar,Muhsin <info@madia.nl>
 * @property   Madia B.V
 * @copyright  Copyright (c) 2011 Madia BV (http://www.madia.nl)
 */
function limitText(field, limit, counterDesired) {
    if (counterDesired == null)
        counterDesired = false;
    var length = $F(field).length;
    if (length > limit)
        $(field).value = $(field).value.substring(0, limit);
    if (counterDesired) {
        if ($($(field).id + '_counter')) {
            $($(field).id + '_counter').update($F(field).length + " / " + limit);
        } else {
            var counterText = new Element('span', {'id': $(field).id + '_counter'});
            counterText.update($F(field).length + " / " + limit);
            $(field).insert({'after': counterText});
        }
    }
}
