<?php

namespace Catrobat\AppBundle\Controller\Api;

use Catrobat\AppBundle\Model\ProgramManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catrobat\AppBundle\Services\Formatter\ElapsedTimeStringFormatter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Catrobat\AppBundle\Entity\FeaturedRepository;
use Catrobat\AppBundle\Services\FeaturedImageRepository;

class FeaturedController extends Controller
{

  /**
   * @Route("/api/projects/featured.json", name="catrobat_api_featured_programs", defaults={"_format": "json"})
   * @Method({"GET"})
   */
  public function searchProgramsAction(Request $request)
  {
    /* @var $image_repository FeaturedImageRepository */
    $image_repository = $this->get('featuredimagerepository');
    /* @var $repository FeaturedRepository */
    $repository = $this->get('featuredrepository');

    $limit = intval($request->query->get('limit'));
    $offset = intval($request->query->get('offset'));

    $programs = $repository->getFeaturedPrograms($limit, $offset);
    $numbOfTotalProjects = $repository->getFeaturedProgramCount();
    
    $retArray = array();
    $retArray['CatrobatProjects'] = array();
    foreach ($programs as $program)
    {
      $new_program = array();
      $new_program['ProjectId'] = $program->getProgram()->getId();
      $new_program['ProjectName'] = $program->getProgram()->getName();
      $new_program['Author'] = $program->getProgram()->getUser()->getUserName();
      $new_program['FeaturedImage'] = $image_repository->getWebPath($program->getId(), $program->getImageType());
      $retArray['CatrobatProjects'][] = $new_program;
    }
    $retArray['preHeaderMessages'] = "";
    $retArray['CatrobatInformation'] = array (
        "BaseUrl" => ($request->isSecure() ? 'https://' : 'http://'). $request->getHttpHost() . $request->getBaseUrl() . '/',
        "TotalProjects" => $numbOfTotalProjects,
        "ProjectsExtension" => ".catrobat" 
    );
    return JsonResponse::create($retArray);
  }
}
