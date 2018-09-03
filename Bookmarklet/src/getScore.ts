import axios from 'axios';
import * as qs from 'qs';

(function () {
  console.log("run");

  const NET_URL = "https://ongeki-net.com/ongeki-mobile/";
  const TOOL_URL = "http://127.0.0.1:8000/api/user/update";

  const REQUEST_KEY = "?t="
  const PRODUCT_NAME = "Project Primera - getScore";
  const VERSION = 1.0;

  class PlayerData {
    trophy: string = "";
    level: number = 0;
    name: string = "";
    battle_point: number = 0;
    rating: number = 0;
    rating_max: number = 0;
    money: number = 0;
    total_money: number = 0;
    total_play: number = 0;
    comment: string = "";
    friend_code: number = 0;

    async getData() {
      await this.getPlayerDataFromNet();
      await this.getFriendCodeDataFromNet();
    }

    private async getPlayerDataFromNet() {
      await axios.get(NET_URL + 'home/playerDataDetail/', {
      }).then(async (response) => {
        await this.parsePlayerData(response.data);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parsePlayerData(html: string) {
      var parseHTML = $.parseHTML(html);
      this.trophy = $(parseHTML).find(".trophy_block").find("span").text();
      this.level = +$(parseHTML).find(".lv_block").find("span").text();
      this.name = $(parseHTML).find(".name_block").find("span").text();
      this.battle_point = +$(parseHTML).find(".battle_rank_block").find("div").text().replace(/,/g, "");
      this.rating = +$(parseHTML).find(".rating_block").find(".rating_field").find("[class^='rating_']").eq(0).text();
      this.rating_max = +$(parseHTML).find(".rating_block").find(".rating_field").find(".f_11").text().replace(/（MAX /g, "").replace(/）/g, "");
      this.money = +$(parseHTML).find(".user_data_detail_block").find("td").eq(2).text().split("（")[0].replace(/,/g, "");
      this.total_money = +$(parseHTML).find(".user_data_detail_block").find("td").eq(2).text().split("（")[1].replace(/累計 /g, "").replace(/）/g, "").replace(/,/g, "");
      this.total_play = +$(parseHTML).find(".user_data_detail_block").find("td").eq(5).text();
      this.comment = $(parseHTML).find(".comment_block").parent().text().replace(/	/g, "").replace("\n", "").replace("\n", "");
    }

    private async getFriendCodeDataFromNet() {
      await axios.get(NET_URL + 'friend/userFriendCode/', {
      }).then(async (response) => {
        await this.parseUserFriendCodeData(response.data);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parseUserFriendCodeData(html: string) {
      var parseHTML = $.parseHTML(html);
      this.friend_code = +$(parseHTML).find(".friendcode_block").text();
    }
  }

  class SongInfo {
    title: string = "";
    genre: string = "";
    level: number = 0;
    over_damage_high_score: number = 0;
    battle_high_score: number = 0;
    technical_high_score: number = 0;
    full_bell: boolean = false;
    all_break: boolean = false;

    constructor(title: string, genre: string, level: number, over_damage_high_score: number, battle_high_score: number, technical_high_score: number, full_bell: boolean, all_break: boolean) {
      this.title = title;
      this.genre = genre;
      this.level = level;
      this.over_damage_high_score = over_damage_high_score;
      this.battle_high_score = battle_high_score;
      this.technical_high_score = technical_high_score;
      this.full_bell = full_bell;
      this.all_break = all_break;
    }
  }

  class ScoreData {
    basicSongInfos = new Array<SongInfo>();
    advancedSongInfos = new Array<SongInfo>();
    expertSongInfos = new Array<SongInfo>();
    masterSongInfos = new Array<SongInfo>();
    lunaticSongInfos = new Array<SongInfo>();

    async getData() {
      await this.getAllDifficultyScoreDataFromNet();
    }

    private async getAllDifficultyScoreDataFromNet() {
      await [0, 1, 2, 3, 10].forEach(async (value, index, array) => {
        await this.getScoreHtmlFromNet(value);
      });
    }

    private async getScoreHtmlFromNet(difficulty: number) {
      await axios.get(NET_URL + 'record/musicGenre/search/', {
        params: {
          genre: 99,
          diff: difficulty
        }
      }).then(async (response) => {
        await this.parseScoreData(response.data, difficulty);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parseScoreData(html: string, difficulty: number) {
      var parseHTML = $.parseHTML(html);
      var $innerContainer3 = $(parseHTML).find(".container3").find("div");

      var genre: string = "";
      await $innerContainer3.each((key, value) => {
        if($(value).hasClass("p_5 f_20")){
          genre = $(value).text();
          
        }else if($(value).hasClass("basic_btn")){
          $(value).each((k, v) => {
            var song = new SongInfo(
              $(v).find(".music_label").text(),
              genre,
              +($(v).find(".score_level").text().replace("+", ".5")),
              +$($(v).find(".score_value")[0]).text().replace(/,/g, "").replace(/%/g, ""),
              +$($(v).find(".score_value")[1]).text().replace(/,/g, ""),
              +$($(v).find(".score_value")[2]).text().replace(/,/g, ""),
              $(v).find("[src*='music_icon_fb.png']").length > 0,
              $(v).find("[src*='music_icon_ab.png']").length > 0,
            );
            switch (difficulty) {
              case 0: this.basicSongInfos.push(song); break;
              case 1: this.advancedSongInfos.push(song); break;
              case 2: this.expertSongInfos.push(song); break;
              case 3: this.masterSongInfos.push(song); break;
              case 10: this.lunaticSongInfos.push(song); break;
            }
          }); 
        }
      });
    }
  }

  class TrophyInfo {
    name: string;
    detail: string;

    constructor(name: string, detail: string) {
      this.name = name;
      this.detail = detail;
    }
  }

  class TrophyData {
    normalTrophyInfos: Array<TrophyInfo> = new Array<TrophyInfo>();
    silverTrophyInfos: Array<TrophyInfo> = new Array<TrophyInfo>();
    goldTrophyInfos: Array<TrophyInfo> = new Array<TrophyInfo>();
    platinumTrophyInfo: Array<TrophyInfo> = new Array<TrophyInfo>();
    rainbowTrophyInfo: Array<TrophyInfo> = new Array<TrophyInfo>();

    async getData() {
      await this.getAllRankTrophyDataFromNet();
    }

    private async getAllRankTrophyDataFromNet() {
      axios.get(NET_URL + 'collection/trophy/', {
      }).then(async (response) => {
        await this.parseAllTrophyData(response.data);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parseAllTrophyData(html: string) {
      var parseHTML = $.parseHTML(html);

      // 虹称号未検証 流石に獲得できない
      await ["Normal", "Silver", "Gold", "Platinum", "Rainbow"].forEach(async (value, index, array) => {
        var $listDiv = $(parseHTML).find("#" + value + "List");
        $listDiv.find(".m_10").each((key, v) => {
          var trophy = new TrophyInfo(
            $($(v).find(".f_14")).text(),
            $($(v).find(".detailText")).text()
          );
          switch (value) {
            case "Normal": this.normalTrophyInfos.push(trophy); break;
            case "Silver": this.silverTrophyInfos.push(trophy); break;
            case "Gold": this.goldTrophyInfos.push(trophy); break;
            case "Platinum": this.platinumTrophyInfo.push(trophy); break;
            case "Rainbow": this.rainbowTrophyInfo.push(trophy); break;
          }
        });
      });
    }
  }

  class CharacterFriendlyData {
    friendly: { [key: string]: number } = {};

    async getData() {
      await this.getCharacterFriendlyDataFromNet();
    }

    private async getCharacterFriendlyDataFromNet() {
      await axios.get(NET_URL + 'character/', {
      }).then(async (response) => {
        await this.parseCharacterFriendlyData(response.data);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parseCharacterFriendlyData(html: string) {
      var parseHTML = $.parseHTML(html);
      var $chara_btn = $(parseHTML).find(".chara_btn");
      await $chara_btn.each((key, value) => {
        var characterID: string = $(value).find("input").val() as string || "";

        var friendlyTensPlace: string = ($(value).find(".character_friendly_conainer").find("img").eq(1).attr('src') || "0").replace("https://ongeki-net.com/ongeki-mobile/img/friendly/num_", "").replace("0.png", "");
        var friendlyUnitsPlace: string = ($(value).find(".character_friendly_conainer").find("img").eq(2).attr('src') || "0").replace("https://ongeki-net.com/ongeki-mobile/img/friendly/num_", "").replace(".png", "");

        this.friendly[characterID] = +(friendlyTensPlace + friendlyUnitsPlace);
      });
    }
  }

  class RecentMusicInfo {
    title: string = "";
    difficulty: number = 0;
    technicalScore: number = 0;

    constructor(title: string, difficulty:number, technicalScore: number) {
      this.title = title;
      this.difficulty = difficulty;
      this.technicalScore = technicalScore;
    }
  }

  class RatingRecentMusicData {
    ratingRecentMusicObject: Array<RecentMusicInfo> = new Array<RecentMusicInfo>();

    async getData() {
      await this.getRatingRecentMusicDataFromNet();
    }

    private async getRatingRecentMusicDataFromNet() {
      await axios.get(NET_URL + 'home/ratingTargetMusic/', {
      }).then(async (response) => {
        await this.parseRatingRecentMusicData(response.data);
      }).catch(function (error) {
        //TODO: エラー処理書く
      });
    }

    private async parseRatingRecentMusicData(html: string) {
      var parseHTML = $.parseHTML(html);
      var $basic_btn = $(parseHTML).find(".basic_btn");
      var count: number = 0;

      await $basic_btn.each((key, value) => {
        if ($(value).html().match(/TECHNICAL SCORE/)) {
          var difficulty:number = -1;
          if($(value).hasClass('lunatic_score_back')){
            difficulty = 10;
          }else if($(value).hasClass('master_score_back')){
            difficulty = 3;
          }else if($(value).hasClass('expert_score_back')){
            difficulty = 2;
          }else if($(value).hasClass('advanced_score_back')){
            difficulty = 1;
          }else if($(value).hasClass('basic_score_back')){
            difficulty = 0;
          }

          var info: RecentMusicInfo = new RecentMusicInfo(
            $(value).find(".music_label").text(),
            difficulty,
            +$(value).find(".score_value").text().replace(/,/g, "")
          );
          this.ratingRecentMusicObject.push(info);
        }
      });
    }
  }
  class AllData {
    PlayerData: PlayerData = new PlayerData();
    ScoreData: ScoreData = new ScoreData();
    TrophyData: TrophyData = new TrophyData();
    CharacterFriendlyData: CharacterFriendlyData = new CharacterFriendlyData();
    RatingRecentMusicData: RatingRecentMusicData = new RatingRecentMusicData();
  }

  var getToken = (function() {
    let url: string;
    if (document.currentScript) {
      url = (document.currentScript as HTMLScriptElement).src;
    } else {
        var scripts = document.getElementsByTagName('script'),
        script = scripts[scripts.length-1];
        if (script.src) {
          url = script.src;
        }else{
          url = "";
        }
    }
    return url.slice(url.indexOf(REQUEST_KEY) + REQUEST_KEY.length);
});

function sleep(milliseconds: number) {
  return new Promise<void>(resolve => {
    setTimeout(() => resolve(), milliseconds);
  });
}


  let main = async () => {
    let allData: AllData = new AllData();

    let token: string = getToken();
    await allData.PlayerData.getData();
    console.log("get PlayerData");
    await sleep(1000);

    await allData.ScoreData.getData();
    console.log("get ScoreData");
    await sleep(1000);

    await allData.TrophyData.getData();
    console.log("get TrophyData");
    await sleep(1000);

    await allData.CharacterFriendlyData.getData();
    console.log("get CharacterFriendlyData");
    await sleep(1000);

    await allData.RatingRecentMusicData.getData();
    console.log("get RatingRecentMusicData");
    await sleep(1000);

    console.log(allData);

    axios.post(TOOL_URL, qs.stringify(allData), {
      headers: { 
        Authorization: "Bearer " + token,
     }
  }).then(response => {
      console.log('body:', response.data);
    });
  }

  main();

})();
