const fs = require("fs");

function range(from, to)
{
    return [...Array(to - from + 1).keys()].map(i => i + from);
}

function mspaRange(from, to)
{
    return range(from, to).map(n => String(n).padStart(6, "0"));
}

function wvRange(id, count)
{
    return range(0, count).map(n => `storyfiles/hs2/waywardvagabond/${id}/0${n}.gif`);
}

// Missing pages: http://readmspa.org/stats/missing.html
const ADVENTURES = {
    "1": {
        "pages": [
            ...mspaRange(2, 6),
            ...mspaRange(8, 135),
            "jb2_000000"
        ]
    },
    "2": {
        "pages": [
            "000136",
            ...mspaRange(171, 216)
        ]
    },
    "3": {
        "pages": [
            "MC0001"
        ]
    },
    "4": {
        "pages": [
            ...mspaRange(219, 991),
            ...mspaRange(993, 1892)
        ]
    },
    "5": {
        "pages": [
            ...mspaRange(1893, 1900)
        ]
    },
    "6": {
        "pages": [
            ...mspaRange(1901, 4298),
            ...mspaRange(4300, 4937),
            ...mspaRange(4939, 4987),
            ...mspaRange(4989, 9801),
            ...mspaRange(9805, 10030),
            "pony",
            "pony2",
            "darkcage",
            "darkcage2"
        ],
        "assets": [
            ...(range(1, 54).map(n => `sweetbroandhellajeff/archive/${String(n).padStart(3, "0")}.jpg`)),
            ...wvRange("anagitatedfinger", 4),
            ...wvRange("anunsealedtunnel", 7),
            ...wvRange("asentrywakens", 5),
            ...wvRange("astudiouseye", 6),
            ...wvRange("beneaththegleam", 2),
            ...wvRange("preparesforcompany", 1),
            ...wvRange("recordsastutteringstep", 6),
            ...wvRange("windsdownsideways", 7),
            // Misc. assets
            "storyfiles/hs2/scraps/pwimg.gif",
            // Misc. Flashes
            "007395/05492.swf",
            "007395/AC_RunActiveContent.js",
            "007680/05777_2.swf",
            "007680/AC_RunActiveContent.js",
            "DOTA/04812.swf",
            "DOTA/AC_RunActiveContent.js",
            "storyfiles/hs2/echidna/echidna.swf",
            "storyfiles/hs2/echidna/AC_RunActiveContent.js",
            "GAMEOVER/06898.swf",
            "GAMEOVER/AC_RunActiveContent.js",
            "sweetbroandhellajeff/movies/SBAHJthemovie1.swf",
            "sweetbroandhellajeff/movies/AC_RunActiveContent.js",
            "shes8ack/07402.swf",
            "shes8ack/AC_RunActiveContent.js",
            // Alterniabound
            "storyfiles/hs2/songs/alterniaboundsongs/A%20Tender%20Moment.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/Alterniabound.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/boyskylark.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/Crustacean.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/ERROR.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/herosgrowth.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/Horschestra.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/MEGALOVANIA.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/Nic_Cage_Romance.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/phrenicphever.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/secretrom.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/spidersclawLOOP2.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/terezistheme.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/THE_NIC_CAGE_SONG.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/VriskasTheme3.mp3",
            "storyfiles/hs2/songs/alterniaboundsongs/walkstabwalk.mp3",
            // Openbound part 1 (007163)
            "storyfiles/hs2/05260/Sburb.min.js",
            "storyfiles/hs2/05260/levels/openbound/openbound.xml",
            "storyfiles/hs2/05260/levels/openbound/firstRoom.xml",
            "storyfiles/hs2/05260/levels/openbound/firstRoomDialog.xml",
            "storyfiles/hs2/05260/levels/openbound/rebubbleText.xml",
            "storyfiles/hs2/05260/levels/openbound/redBubbleText.xml",
            "storyfiles/hs2/05260/levels/openbound/standardTemplates.xml",
            "storyfiles/hs2/05260/resources/openbound/audio/music/FuschiaRulerQuiet.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/Close1.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/Grind.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/HitPowerful.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/HitWeak.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/ItemGetNoVoice.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/ItemGetVoice.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/Open1.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/Open2.ogg",
            "storyfiles/hs2/05260/resources/openbound/audio/sfx/Staircase.ogg",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_11.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_12.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_13.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_14.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_15.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_21.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_22.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_23.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_24.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_25.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_31.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_32.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_33.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_34.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_35.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_41.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_42.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_43.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_44.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomBG/MAP1_45.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_11.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_12.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_13.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_14.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_15.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_21.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_22.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_23.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_24.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_25.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_31.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_32.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_33.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_34.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_35.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_41.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_42.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_43.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_44.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomFG/MAP1o_45.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/firstRoomMap.png",
            "storyfiles/hs2/05260/resources/openbound/backgrounds/load.png",
            "storyfiles/hs2/05260/resources/openbound/chars/ah_sit.png",
            "storyfiles/hs2/05260/resources/openbound/chars/andrew.png",
            "storyfiles/hs2/05260/resources/openbound/chars/aradia.png",
            "storyfiles/hs2/05260/resources/openbound/chars/meenah.png",
            "storyfiles/hs2/05260/resources/openbound/chars/meenah_skate.png",
            "storyfiles/hs2/05260/resources/openbound/cutscenes/intro.swf",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_happy.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_happytalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_idle.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_laugh.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_point.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_talk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/aradia_wink.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_angry.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_angrytalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_annoyed.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_annoyedtalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_creepy.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_creepylaugh.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_creepytalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_fish.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_fishtalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_happier.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_happy.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_happytalk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_idle.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_ohyes.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_talk.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_wink.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_wut.png",
            "storyfiles/hs2/05260/resources/openbound/dialogs/meenah_wut2.png",
            "storyfiles/hs2/05260/resources/openbound/effects/teleportSheet.png",
            "storyfiles/hs2/05260/resources/openbound/interface/alttextbox_twitter.png",
            "storyfiles/hs2/05260/resources/openbound/interface/closeconversation.png",
            "storyfiles/hs2/05260/resources/openbound/interface/dialogBoxBig.png",
            "storyfiles/hs2/05260/resources/openbound/interface/hashtagbar.png",
            "storyfiles/hs2/05260/resources/openbound/interface/helpControl.png",
            "storyfiles/hs2/05260/resources/openbound/interface/icons_bub.png",
            "storyfiles/hs2/05260/resources/openbound/interface/icons_heart.png",
            "storyfiles/hs2/05260/resources/openbound/interface/icons_spade.png",
            "storyfiles/hs2/05260/resources/openbound/interface/volumeControl.png",
            "storyfiles/hs2/05260/resources/openbound/objects/01.png",
            "storyfiles/hs2/05260/resources/openbound/objects/02.png",
            "storyfiles/hs2/05260/resources/openbound/objects/03.png",
            "storyfiles/hs2/05260/resources/openbound/objects/04.png",
            "storyfiles/hs2/05260/resources/openbound/objects/05.png",
            "storyfiles/hs2/05260/resources/openbound/objects/06.png",
            "storyfiles/hs2/05260/resources/openbound/objects/07.png",
            "storyfiles/hs2/05260/resources/openbound/objects/08.png",
            "storyfiles/hs2/05260/resources/openbound/objects/09.png",
            "storyfiles/hs2/05260/resources/openbound/objects/10.png",
            "storyfiles/hs2/05260/resources/openbound/objects/ah_8balls.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest1.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest1o.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest4.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest4o.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest5.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest5o.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest6.png",
            "storyfiles/hs2/05260/resources/openbound/objects/chest6o.png",
            "storyfiles/hs2/05260/resources/openbound/objects/redbubble.png",
            "storyfiles/hs2/05260/resources/openbound/objects/teleporterpad.png",
            // Openbound part 2 (007208)
            "storyfiles/hs2/05305/Sburb.min.js",
            "storyfiles/hs2/05305/levels/init.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/andrew.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/bicyclops.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/cronus.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/kankri.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/meenah.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/mituna.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/chars/seahorsedad.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/dialogs/cronusDialog.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/dialogs/meenahDialog.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/dialogs/mitunaDialog.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/etc/sfx.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/etc/standardTemplates.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/etc/ui.xml",
            "storyfiles/hs2/05305/levels/openbound_p2/rooms/firstRoom.xml",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/GameBroLoop.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/GameBroLoop.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/HateYouLoop.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/HateYouLoop.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/VioletPrinceLoop.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/music/VioletPrinceLoop.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Close1.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Close1.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Grind.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Grind.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/HitPowerful.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/HitPowerful.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/HitWeak.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/HitWeak.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/ItemGetNoVoice.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/ItemGetNoVoice.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/ItemGetVoice.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/ItemGetVoice.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Open1.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Open1.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Open2.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Open2.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Staircase.mp3",
            "storyfiles/hs2/05305/resources/openbound_p2/audio/sfx/Staircase.oga",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/firstRoomFG.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/firstRoomMap.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_0_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_0_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_0_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_10_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_10_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_10_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_11_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_11_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_11_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_12_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_12_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_12_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_1_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_1_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_1_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_2_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_2_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_2_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_3_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_3_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_3_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_4_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_4_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_4_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_5_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_5_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_5_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_6_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_6_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_6_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_7_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_7_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_7_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_8_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_8_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_8_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_9_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_9_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomBG_9_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_0_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_0_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_10_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_10_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_11_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_1_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_1_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_2_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_2_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_2_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_3_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_3_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_3_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_4_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_4_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_5_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_5_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_5_2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_6_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_6_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_7_0.png",
            "storyfiles/hs2/05305/resources/openbound_p2/backgrounds/slices/firstRoomFG_7_1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/andrew/ah_sit.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/andrew/andrew.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/bicyclops/bicyclops-stamp.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/cronus/cronus.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/meenah/meenah.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/mituna/mituna.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/mituna/mituna_skate1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/mituna/mituna_skate2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/seahorsedad/AHseahorse.png",
            "storyfiles/hs2/05305/resources/openbound_p2/chars/seahorsedad/seahorsedad.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_angry.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_bored.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_happy.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_happytalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_huh.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_idle.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_sad.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_sad2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_smug.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_surprised.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_talk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_upset.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/cronus/cronus_upset2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_glance.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_idle.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_pray.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_rage.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_talk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_talk2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_talk3.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/kankri/kankri_talk4.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_angry.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_angrytalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_annoyed.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_annoyedtalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_creepy.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_creepylaugh.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_creepytalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_fish.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_fishtalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_happier.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_happy.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_happytalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_idle.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_ohyes.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_talk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_wink.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_wut.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/meenah/meenah_wut2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_agitated.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_angry.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_angry2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_facepalm.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_fall.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_happy.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_happytalk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_idle.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_laugh.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_sad.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_shine.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_spaz1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_spaz2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/dialogs/mituna/mituna_talk.png",
            "storyfiles/hs2/05305/resources/openbound_p2/fonts/cour.ttf",
            "storyfiles/hs2/05305/resources/openbound_p2/fonts/cour.woff",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_cronus1.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_cronus2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_cronus3.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_cronus4.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_mituna1.gif",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_mituna2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/dialogimg_mituna3.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/backgrounds/meenahmeme.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/boxes/alttextbox_4chan.gif",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/boxes/alttextbox_twitter.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/boxes/dialogBoxBig.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/closeconversation.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/hashtagbar.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/helpControl.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/icons_bub.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/icons_heart.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/icons_spade.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/load.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/save_icon.png",
            "storyfiles/hs2/05305/resources/openbound_p2/interface/volumeControl.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/bluebubble.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest2.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest2o.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest3.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest3o.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest8.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/chest8o.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/door_closed.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/door_open.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/doorkey_club.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/doorkey_diamond.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/doorkey_heart.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/doorkey_spade.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj01.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj02.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj03.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj04.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj05.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj06.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj07.png",
            "storyfiles/hs2/05305/resources/openbound_p2/objects/items/obj08.png",
            // Openbound part 3 (007298)
            "storyfiles/hs2/05395/Sburb.min.js",
            "storyfiles/hs2/05395/levels/init.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/damara.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/horuss.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/kanaya.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/kankri.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/latula.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/meenah.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/mituna.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/chars/rufioh.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/dialogs/damaraDialog.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/dialogs/horussDialog.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/dialogs/kanayaDialog.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/dialogs/meenahDialog.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/dialogs/rufiohDialog.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/etc/sfx.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/etc/standardTemplates.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/etc/ui.xml",
            "storyfiles/hs2/05395/levels/openbound_p3/rooms/firstRoom.xml",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/IndigoHeirLoop.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/IndigoHeirLoop.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/JadeSylphLoop.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/JadeSylphLoop.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/MeenahLoop.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/MeenahLoop.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/RustMaidLoop.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/RustMaidLoop.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/SummonerLoop.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/music/SummonerLoop.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Close1.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Close1.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Grind.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Grind.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/HitPowerful.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/HitPowerful.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/HitWeak.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/HitWeak.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/ItemGetNoVoice.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/ItemGetNoVoice.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/ItemGetVoice.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/ItemGetVoice.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Open1.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Open1.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Open2.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Open2.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Staircase.mp3",
            "storyfiles/hs2/05395/resources/openbound_p3/audio/sfx/Staircase.oga",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/firstRoomMap.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/qwartz.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_0_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_0_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_0_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_0_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_1_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_1_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_1_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_1_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_2_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_2_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_2_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_2_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_3_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_3_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_3_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_3_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_4_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_4_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_4_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_4_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_5_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_5_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_5_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomBG_5_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_0_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_0_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_0_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_1_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_1_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_1_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_2_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_2_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_2_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_2_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_3_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_3_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_3_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_4_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_4_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_4_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_4_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_5_0.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_5_1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_5_2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/backgrounds/slices/firstRoomFG_5_3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/damara/damara.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/horuss/horuss.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/kanaya/kanaya.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/latula/latula.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/meenah/meenah.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/mituna/mituna.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/mituna/mituna_skate1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/mituna/mituna_skate2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/chars/rufioh/rufioh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_fiendish.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_fu1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_fu2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_fu3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_fu4.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_huh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_huhtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_lewd.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_mean.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_meantalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_pissed.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_sad.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_sadtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_smile.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_smilemean.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_smilemeantalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_smiletalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_smoke.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_squint.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_squinttalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/damara/damara_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_bashful.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_crossed.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_crossed2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_grin.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_hmm.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_hmmtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_horsedick.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_horsedick_blur1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_horsedick_blur2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_horsedick_blur3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_idle2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_idle2talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_laugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_mad.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_oops.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sad.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sadtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_smile.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_smiletalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sweat_bashful.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sweat_bashful2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sweat_grin.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sweat_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/horuss/horuss_sweat_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_bored.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_cry.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_facepalm.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_smirklaugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_smirktalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya2_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_angrytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_bored.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_cry.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_facepalm.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_smirklaugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_smirktalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kanaya/kanaya_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_glance.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_pray.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_rage.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_talk2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_talk3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_talk4.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/kankri/kankri_whistle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_angry.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_annoyed.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_annoyedtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_bored.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_happier.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_huh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_idle2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_idle3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_laugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_shades1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_shades2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_shine.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_stunt.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/latula/latula_tongue.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_angry.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_angrytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_annoyed.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_annoyedtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_creepy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_creepylaugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_creepytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_fish.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_fishtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_happier.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_ohyes.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_wink.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_wut.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/meenah/meenah_wut2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_agitated.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_angry.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_angry2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_facepalm.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_fall.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_laugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_sad.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_shine.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_spaz1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_spaz2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/mituna/mituna_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_happy.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_happytalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_idle.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_laugh.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_no.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_notalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_offended.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_offendedtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_sad.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_sadtalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_sheepish.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_surprise.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_surprisetalk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/dialogs/rufioh/rufioh_talk.png",
            "storyfiles/hs2/05395/resources/openbound_p3/fonts/cour.ttf",
            "storyfiles/hs2/05395/resources/openbound_p3/fonts/cour.woff",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/backgrounds/dialogimg_kankri.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/backgrounds/dialogimg_sneak.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/boxes/alttextbox_twitter.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/boxes/dialogBoxBig.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/closeconversation.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/hashtagbar.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/helpControl.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/icons_bub.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/icons_heart.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/icons_spade.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/load.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/save_icon.png",
            "storyfiles/hs2/05395/resources/openbound_p3/interface/volumeControl.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/bluebubble.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/chests/chest7.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/chests/chest7o.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/chests/chest8.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/chests/chest8o.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/horsaponi.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/fidusspawneggs.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item2.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item3.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item4.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item5.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/item6.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/items/tinkerbull.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/latula.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/plush1.png",
            "storyfiles/hs2/05395/resources/openbound_p3/objects/plush2.png",
        ]
    },
    "ryanquest": {
        "pages": [
            ...mspaRange(1, 15)
        ]
    }
};

(async function(){

console.log();
console.log("MSPA To Go Dumper");
console.log("For offline mobile reading");
console.log();

let args = process.argv.slice(2);
if (args.length == 0)
{
    console.log("Usage: node dump.js <parts>");
    console.log("");
    console.log("Possible parts:");
    console.log("- 1 (Jailbreak)");
    console.log("- 2 (Bard Quest)");
    console.log("- 3 (Blood Spade)");
    console.log("- 4 (Problem Sleuth)");
    console.log("- 5 (Homestuck BETA)");
    console.log("- 6 (Homestuck + SBAHJ)");
    console.log("- ryanquest");
    console.log("- all: To dump everything")
    return;
}


let selectedParts = [];
if (args.length == 1 && args[0] == "all")
{
    selectedParts = Object.keys(ADVENTURES);
}
else
{
    for (arg of args)
    {
        if (arg in ADVENTURES)
        {
            if (!selectedParts.includes(arg))
                selectedParts.push(arg);
        }
        else
        {
            console.log(`Invalid part: ${arg}`);
            return;
        }
    }
}

console.log(`Selected parts: ${selectedParts.join(", ")}`);
console.log("");

function makeDir(p)
{
    fs.mkdirSync(p, { recursive: true });
}

async function downloadFile(file, noCdn = false, noFail = false)
{
    if (fs.existsSync(OUTPUT_DIR + file))
        return;

    let r = await fetch(`http://${noCdn ? "www" : "cdn"}.mspaintadventures.com/${file}`);
    if (r.status != 200 && !noCdn)
        return await downloadFile(file, true);
    else if (!noFail && r.status != 200)
    {
        console.log(`Failed to get file ${file} with status ${r.status}`);
        process.exit(0);
    }
    
    if (r.status != 200)
        return;

    console.log(file);
    let dir = file.substring(0, file.lastIndexOf("/") + 1);
    makeDir(OUTPUT_DIR + dir);
    fs.writeFileSync(OUTPUT_DIR + file, await r.bytes());
}

const OUTPUT_DIR = __dirname + "/../mspa_local/";
makeDir(OUTPUT_DIR);

const IMAGE_URL_REGEX = /(?<!F\||S\|)http:\/\/(?:cdn.|www.|)mspaintadventures.com\/([^\?]*?)(?:$|\"|\'|<)/gm;
const FLASH_URL_REGEX = /(?<=F\|)http:\/\/(?:cdn.|www.|)mspaintadventures.com\/([^\?]*?)(?:$|\")/gm;
const SUPER_URL_REGEX = /(?<=S\|)http:\/\/(?:cdn.|www.|)mspaintadventures.com\/([^\?]*?)(?:$|\")/gm;

for (part of selectedParts)
{
    let partData = ADVENTURES[part];

    console.log(`Downloading log/map/search files for adventure ${part}`);
    await downloadFile(`logs/log_${part}.txt`, true, true);
    await downloadFile(`logs/log_rev_${part}.txt`, true, true);
    if (part == "6")
    {
        await downloadFile("search/search_6_1.txt", true, true);
        await downloadFile("search/search_6_2.txt", true, true);
        await downloadFile("search/search_6_3.txt", true, true);
    }
    else
    {
        await downloadFile(`search/search_${part}.txt`, true, true);
    }
    await downloadFile(`maps/${part}.html`, true, true);
    if (fs.existsSync(`${OUTPUT_DIR}maps/${part}.html`))
    {
        let map = fs.readFileSync(`${OUTPUT_DIR}maps/${part}.html`).toString();
        for (const match of map.matchAll(IMAGE_URL_REGEX))
        {
            let path = match[1];
            if (path.endsWith(".gif"))
                await downloadFile(path);
        }
    }
    console.log("");

    if ("pages" in partData)
    {
        console.log(`Downloading pages for adventure ${part}:`);

        for (page of partData.pages)
        {
            await downloadFile(`${part}/${page}.txt`, true);
            await downloadFile(`${part}_back/${page}.txt`, true, true);
        }
        console.log();

        console.log(`Downloading page assets for adventure ${part}:`);

        for (page of partData.pages)
        {
            let content = fs.readFileSync(`${OUTPUT_DIR}${part}/${page}.txt`).toString();

            for (const match of content.matchAll(IMAGE_URL_REGEX))
            {
                let path = match[1];
                await downloadFile(path);
            }

            for (const match of content.matchAll(FLASH_URL_REGEX))
            {
                let prefix = match[1];
                let split = prefix.split("/");
                let id = split[split.length - 1];
                let swfPath = `${prefix}/${id.slice(id.length - 5)}.swf`;
                let jsPath = `${prefix}/AC_RunActiveContent.js`;
                await downloadFile(swfPath);
                await downloadFile(jsPath);
            }
        
            for (const match of content.matchAll(SUPER_URL_REGEX))
            {
                let path = match[1] + "/index.html";
                await downloadFile(path);
                let superContent = fs.readFileSync(OUTPUT_DIR + path).toString();
                for (const superMatch of superContent.matchAll(IMAGE_URL_REGEX))
                {
                    let superPath = superMatch[1];
                    // Paths without extension assumed to be SWFs
                    if (-1 == superPath.indexOf("."))
                        superPath += ".swf";
                    await downloadFile(superPath);
                }
            }
        }

        console.log();
    }

    if ("assets" in partData)
    {
        console.log(`Downloading misc. files for adventure ${part}`);
        for (asset of partData.assets)
        {
            console.log(asset);
            await downloadFile(asset);
        }
    }
}

})();