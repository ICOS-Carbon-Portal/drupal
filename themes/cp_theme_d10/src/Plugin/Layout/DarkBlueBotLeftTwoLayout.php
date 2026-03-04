namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "dark_blue_bot_left_two_layout",
 *   label = @Translation("Two column with dark blue box"),
 *   category = "CP",
 *   template = "layouts/dark-blue-bot-left-two",
 *   regions = {
 *     "left_top" = {
 *       "label" = @Translation("Left, top")
 *     },
 *     "left_bot" = {
 *       "label" = @Translation("Left, bottom (dark cyan box)")
 *     },
 *     "right" = {
 *       "label" = @Translation("Right")
 *     }
 *   }
 * )
 */
class DarkBlueBotLeftTwoLayout extends LayoutDefault {
}