<?php

namespace Drupal\pdfview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for output PDF in browser.
 */
class PDFViewOutput extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function data() {

    //$public_realpath = \Drupal::service('file_system')->realpath("public://");

    $passed_path = $_GET['f'];
    $redirect_path = '/';

    if ($passed_path) {
      $redirect_path = $passed_path;

      if (strpos($passed_path, '/sites/default/files/') === 0) {

        $fs_path = str_replace('/sites/default/files/', 'public://',
            $passed_path);

        $filename = array_reverse(explode('/', $passed_path))[0];

        if (count(explode('.', $filename)) > 1) {

          $extension = array_reverse(explode('.', $filename))[0];

          if ($extension == 'pdf') {

            $file_contents = file_get_contents($fs_path);

            if ($file_contents) {

              $response = new Response();

              $response->setContent($file_contents);

              $response->headers->set('Content-Type', 'application/pdf');
              $response->headers->set('Content-Disposition',
                  'attachment; filename="' . $filename . '"');
              $response->headers->set('Content-Transfer-Encoding', 'binary');
              $response->headers->set('Accept-Ranges', 'bytes');

              return $response;
            }
          }
        }
      }
    }


    $response = new RedirectResponse($redirect_path);

    return $response;
  }

}
