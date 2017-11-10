<?php
/**
 * @file
 * Contains \Drupal\middlecontentblock\Plugin\Block\MiddlecontentBlock.
 */
namespace Drupal\middlecontentblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 *
 * @Block(
 *   id = "middle_content_block",
 *   admin_label = @Translation("Middle Content Block"),
 *   category = @Translation("Custom")
 * )
 */
class MiddlecontentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  	return [
    '#type' => 'markup',
    '#markup' => '<div class="row">
    <div class="col-lg-12">
      <div class="big-cta">
        <div class="cta-text">
          <h2><span>Moderna</span> HTML Business Template</h2>
        </div>
      </div>
    </div>
  </div>
  '
  ];
}
}