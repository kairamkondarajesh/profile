<?php
/**
 * @file
 * Contains \Drupal\copyrightblock\Plugin\Block\CopyrightBlock.
 */
namespace Drupal\copyrightblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 *
 * @Block(
 *   id = "footer_copyright_block",
 *   admin_label = @Translation("Footer Copyright Block"),
 *   category = @Translation("Custom")
 * )
 */
class CopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  	return [
  		'#type' => 'markup',
  		'#markup' => '<p>© Moderna Theme. All right reserved.</p>
                    <p><a href="https://bootstrapmade.com/">Free Bootstrap Themes</a> by 
                    <a href="https://bootstrapmade.com/">BootstrapMade</a></p>'
		];
  }
}