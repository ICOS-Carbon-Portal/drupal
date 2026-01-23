namespace Drupal\cp_theme_d10\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\LayoutBuilder\SectionStorageInterface;
use Drupal\LayoutBuilder\Section;

/**
 * @Layout(
 *   id = "video_layout",
 *   label = @Translation("Video background layout"),
 *   category = "CP",
 *   template = "layouts/video",
 *   regions = {
 *     "text" = {
 *       "label" = @Translation("Overlaid Text")
 *     }
 *   }
 * )
 */
class VideoLayout extends LayoutDefault {
}