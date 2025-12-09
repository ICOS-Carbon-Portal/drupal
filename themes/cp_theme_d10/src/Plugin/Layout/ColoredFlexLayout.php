namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "colored_flex_layout",
 *   label = @Translation("Colored Flex Section (two column)"),
 *   category = "CP",
 *   template = "layouts/colored-flex",
 *   regions = {
 *     "left" = {
 *       "label" = @Translation("Left box (dark cyan)")
 *     },
 *     "right" = {
 *       "label" = @Translation("Right box (light cyan)")
 *     }
 *   }
 * )
 */
class ColoredFlexLayout extends LayoutDefault {
}