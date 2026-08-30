<?php

// here we are defining configuration.

use chillerlan\QRCode\QROptions; // start using QRCode
use \chillerlan\QRCode\Common\EccLevel;

// defining QRCode settings
$QROptions                       = new QROptions;
$QROptions->outputBase64         = false;
$QROptions->scale                = 100;  // scale of the output
$QROptions->imageTransparent     = true; // transparent background
$QROptions->drawLightModules     = false;
$QROptions->svgUseFillAttributes = true;

$QROptions->connectPaths = true;

$QROptions->svgDefs = '
 <linearGradient id="rainbow" x1="1" y2="1">
  <stop stop-color="#e2453c" offset="0"/>
  <stop stop-color="#e07e39" offset="0.2"/>
  <stop stop-color="#e5d667" offset="0.4"/>
  <stop stop-color="#51b95b" offset="0.6"/>
  <stop stop-color="#1e72b7" offset="0.8"/>
  <stop stop-color="#6f5ba7" offset="1"/>
 </linearGradient>
 <style><![CDATA[
  .dark{fill: url(#rainbow);}
  .light{fill: #eee;}
 ]]></style>';
// you can customise it yes.
