namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "lblue_three_col_layout",
 *   label = @Translation("Light Blue Section (three column)"),
 *   category = "CP",
 *   template = "layouts/light-blue-three",
 *   regions = {
 *     "one" = {
 *       "label" = @Translation("Column 1")
 *     },
 *     "two" = {
 *       "label" = @Translation("Column 2")
 *     },
 *     "three" = {
 *       "label" = @Translation("Column 3")
 *     }
 *   }
 * )
 */
class LightBlueThreeColumnLayout extends LayoutDefault {
}