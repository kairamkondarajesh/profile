<?php

namespace Drupal\addressblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Addressblock class.
 *
 * @Block(
 *   id = "footer_address_block",
 *   admin_label = @Translation("Footer Address Block"),
 *   category = @Translation("Custom")
 * )
 */
class AddressBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'markup',
      '#markup' => '<h5>Get in touch with us</h5>
  			<p><strong>Moderna company Inc</strong><br />
  			Modernbuilding suite V124, AB 01<br />
  			Someplace 16425 Earth</p>
  			<p>(123) 456-7890 - (123) 555-7891<br />
  				email@domainname.com</p>',
    ];
  }

}
