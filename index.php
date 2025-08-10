<?php
namespace MSPAToGo\Controller;
require "vendor/autoload.php";
require "include/autoload.php";

use MSPAToGo\ServerConfig;
use MSPAToGo\ControllerManager;

ServerConfig::ensure();

ControllerManager::route([
    "/"                        => HomeController::class,
    "/jailbreak"               => VIZRedirectController::class,
    "/jailbreak/*"             => VIZRedirectController::class,
    "/bard-quest"              => VIZRedirectController::class,
    "/bard-quest/*"            => VIZRedirectController::class,
    "/blood-spade"             => VIZRedirectController::class,
    "/blood-spade/*"           => VIZRedirectController::class,
    "/problem-sleuth"          => VIZRedirectController::class,
    "/problem-sleuth/*"        => VIZRedirectController::class,
    "/beta"                    => VIZRedirectController::class,
    "/beta/*"                  => VIZRedirectController::class,
    "/homestuck"               => VIZRedirectController::class,
    "/homestuck/*"             => VIZRedirectController::class,
    "/ryanquest"               => VIZRedirectController::class,
    "/ryanquest/*"             => VIZRedirectController::class,
    "/mspa/*"                  => MSPAFunnelController::class,
    // Weird Openbound relative URLs
    "/read/6/storyfiles/hs2/*" => MSPAFunnelController::class,
    "/archive"                 => ArchiveController::class,
    "/log"                     => LogController::class,
    "/log/*"                   => LogController::class,
    "/map"                     => MapController::class,
    "/map/*"                   => MapController::class,
    "/search"                  => SearchController::class,
    "/search/*"                => SearchController::class,
    "/read/*"                  => ReadController::class,
    "/waywardvagabond/*"       => WVController::class,
    "/oilretcon"               => OilRetconController::class,
    "/privacy"                 => PrivacyController::class,
    "/legal"                   => LegalController::class,
    "/credits"                 => CreditsController::class,
    "/options"                 => OptionsController::class,
    "/sbahj"                   => SBAHJController::class,
    "/sbahj/*"                 => SBAHJController::class,
    "/SBAHJthemovie1"          => SBAHJMovieController::class,
    "default"                  => NotFoundController::class,
]);
ControllerManager::run();