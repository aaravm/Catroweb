<?php

namespace Catrobat\CatrowebBundle\Twig;

use Catrobat\CatrowebBundle\Helper\ScreenshotRepository;

class ProjectAssetsExtension extends \Twig_Extension
{
  private $screen_repo;
  
  public function __construct(ScreenshotRepository $screen_repo)
  {
    $this->screen_repo = $screen_repo;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFunctions()
  {
    return array(
        'thumbnail_url' => new \Twig_Function_Method($this, 'getProjectThumbnail')
    );
  }
   
  /**
   * Converts a string to time
   *
   * @param string $string
   * @return int
   */
  public function getProjectThumbnail($id)
  {
    return $this->screen_repo->getScreenshotWebPath($id);
  }
  
  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName()
  {
    return 'project_assets';
  }
}