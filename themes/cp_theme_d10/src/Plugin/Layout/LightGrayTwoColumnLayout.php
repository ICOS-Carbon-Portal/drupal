namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "lgray_two_col_layout",
 *   label = @Translation("Light Gray Section (two column)"),
 *   category = "CP",
 *   template = "layouts/light-gray-two",
 *   regions = {
 *     "one" = {
 *       "label" = @Translation("Column 1")
 *     },
 *     "two" = {
 *       "label" = @Translation("Column 2")
 *     }
 *   }
 * )
 */
class LightGrayTwoColumnLayout extends LayoutDefault {
}