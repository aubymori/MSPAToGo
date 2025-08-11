<?php
namespace MSPAToGo\Controller;
require "vendor/autoload.php";
require "include/autoload.php";

use MSPAToGo\ServerConfig;
use MSPAToGo\ControllerManager;
use MSPAToGo\MSPALinks;

ServerConfig::registerTwigFunctions();
MSPALinks::registerTwigFunctions();

ControllerManager::route([
    "/"                                 => HomeController::class,
    "/mspa/*"                           => MSPAFunnelController::class,
    // Weird Openbound relative URLs
    "/read/6/storyfiles/hs2/*"          => MSPAFunnelController::class,
    "/homestuck/storyfiles/hs2/*"       => MSPAFunnelController::class,
    "/jailbreak"                        => ReadController::class,
    "/jailbreak/*"                      => ReadController::class,
    "/bard-quest"                       => ReadController::class,
    "/bard-quest/*"                     => ReadController::class,
    "/blood-spade"                      => ReadController::class,
    "/blood-spade/*"                    => ReadController::class,
    "/problem-sleuth"                   => ReadController::class,
    "/problem-sleuth/*"                 => ReadController::class,
    "/beta"                             => ReadController::class,
    "/beta/*"                           => ReadController::class,
    "/homestuck"                        => ReadController::class,
    "/homestuck/*"                      => ReadController::class,
    "/ryanquest"                        => ReadController::class,
    "/ryanquest/*"                      => ReadController::class,
    "/read/*"                           => ReadController::class,
    "/archive"                          => ArchiveController::class,
    "/log"                              => LogController::class,
    "/log/*"                            => LogController::class,
    "/map"                              => MapController::class,
    "/map/*"                            => MapController::class,
    "/search"                           => SearchController::class,
    "/search/*"                         => SearchController::class,
    "/waywardvagabond/*"                => WVController::class,
    "/oilretcon"                        => OilRetconController::class,
    "/privacy"                          => PrivacyController::class,
    "/legal"                            => LegalController::class,
    "/credits"                          => CreditsController::class,
    "/options"                          => OptionsController::class,
    "/sbahj"                            => SBAHJController::class,
    "/sbahj/*"                          => SBAHJController::class,
    "/SBAHJthemovie1"                   => SBAHJMovieController::class,
    "default"                           => NotFoundController::class,
]);
ControllerManager::run();