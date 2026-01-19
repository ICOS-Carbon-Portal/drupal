namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "lblue_two_col_layout",
 *   label = @Translation("Light Blue Section (two column)"),
 *   category = "CP",
 *   template = "layouts/light-blue-two",
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
class LightBlueTwoColumnLayout extends LayoutDefault {
}