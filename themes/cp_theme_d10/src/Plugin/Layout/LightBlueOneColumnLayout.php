namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "lblue_one_col_layout",
 *   label = @Translation("Light Blue Section (one column)"),
 *   category = "CP",
 *   template = "layouts/light-blue-one",
 *   regions = {
 *     "one" = {
 *       "label" = @Translation("Column 1")
 *     }
 *   }
 * )
 */
class LightBlueOneColumnLayout extends LayoutDefault {
}