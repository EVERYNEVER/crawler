<?php

namespace App;

use Core\Component;

/**
 * 四中模板类型对应例子网址
 * 1：http://liweiguang.artron.net/
 * 2：http://artist.artron.net/yishujia0210004，http://artist.artron.net/yishujia0266662/
 * 3：http://datusanyang.artron.net/main.php?aid=A0258530
 * 4：http://aijing.artron.net/
 */

class Yachang
{
    private $db;

    private $filter;

    private $crawler;

    //init钩子，当爬虫初始化后运行的钩子
    public function hookInit()
    {
        $this->crawler = Component::Crawler();
        $this->filter = Component::Filter();
        $_this = $this;

        $urlRule = [
            0 => '/^http:\/\/artist.artron.net\/class-0-0-[0-9]+.html$/',//列表页
            1 => '/^http:\/\/[^\s]+\.artron\.net\/?$/',//第1种模板和第4种模板的艺术家官网
            2 => '/^http:\/\/[^\s]+\.artron\.net\/about$/',//艺术家官网艺术家介绍
            3 => '/^http:\/\/[^\s]+\.artron\.net\/works$/',//艺术家官网作品列表页
            4 => '/^http:\/\/[^\s]+\.artron\.net\/works_category_[0-9]+(_[0-9]+)?$/',//艺术家官网作品分类下的列表页
            5 => '/^http:\/\/[^\s]+\.artron\.net\/works_detail_brt[0-9]+$/',//艺术家官网作品详情页
            6 => '/^http:\/\/[^\s]+\.artron\.net\/exhibit(_index_[0-9]+)?$/',//艺术家官网展览列表页
            7 => '/^http:\/\/[^\s]+\.artron\.net\/exhibition-[0-9]+(\.html)?$/',//艺术家官网展览详情页
            8 => '/^http:\/\/[^\s]+\.artron\.net\/market(_index_[0-9]+)?$/',//艺术家官网拍卖行情
            9 => '/^http:\/\/[^\s]+\.artron\.net\/paimai-art[0-9]+(\/)?$/',//艺术家官网拍卖详情页
            10 => '/^http:\/\/[^\s]+\.artron\.net\/news(_index_[0-9]+)?$/',//艺术家官网资讯列表页
            11 => '/^http:\/\/[^\s]+\.artron\.net\/news_detail_[0-9]+$/',//艺术家官网资讯详情页
            12 => '/^http:\/\/[^\s]+\.artron\.net\/photo(_index_[0-9]+)?$/',//艺术家官网相册列表页
            13 => '/^http:\/\/[^\s]+\.artron\.net\/photo_detail_[0-9]+(_[0-9]+)?$/',//艺术家官网相册详情页
            14 => '/^http:\/\/[^\s]+\.artron\.net\/collect$/',//艺术家官网收藏捐赠列表页
            15 => '/^http:\/\/[^\s]+\.artron\.net\/book(_index_[0-9]+)?$/',//艺术家官网出版著作列表页
            16 => '/^http:\/\/[^\s]+\.artron\.net\/book_pubinfo_[0-9]+$/',//艺术家官网出版著作详情页
            17 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/?$/',//第2种模板的艺术家官网
            18 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/resume\.html$/',//第2种模板的艺术简历栏目
            19 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/news-([0-9]+)?(\.html\#auctionTarget)?(\/)?$/',//第2种模板的市场行情列表页
            20 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/sale(\-[0-9]+\-[0-9]+\-[0-9]+\-[0-9]+\-[0-9]+)?(\/)?$/',//第2种模板的作品列表页
            21 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/2\-[0-9]+\.html(\#w)?$/',//第2种模板的作品详情页
            22 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/exhibition.html$/',//第2种模板参展经历页面
            23 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/info(\-[0-9]+\-[0-9]+)?(\/)?$/', //第2种模板资讯列表页
            24 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/3\-[0-9]+\.html$/',//第2种模板资讯详情页
            25 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/album(\/)?$/',//第2种模板相册列表首页
            26 => '/^http:\/\/artist.artron.net\/yishujia[0-9]+\/album\-0\-[0-9]+(\/)?$/',//第2种模板相册列表分页
        ];
        $hrefRule = [
            0 => function($htmldom){
                //列表页获取艺术家个人官网的链接和列表分页链接
                $href = [];
                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }
                foreach($htmldom->find(".pic_set a") as $v) {
                    $href[] = $v->href;
                }

                return $href;
            },
            1 => function($htmldom) use ($_this){
                //第1种模板和第4种模板首页获取栏目链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".box980 ul li a") as $v){
                        $href[] = $v->href;
                    }
                } elseif($style == 4){
                    foreach($htmldom->find("#indexNavigation a") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            2 => function($htmldom){
            },
            3 => function($htmldom) use ($_this){
                //第1种模板作品栏目获取作品分类链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".left .float dl dt a[title]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            4 => function($htmldom) use ($_this){
                //第1种模板作品分类栏目获取分页链接和作品链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".worksList .page a[target]") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".mainLRWorks .right .worksList ul li p a") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            5 => function($htmldom){
            },
            6 => function($htmldom) use ($_this){
                //第1种模板展览栏目下获取分页链接和内容链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".content .tabsCon .aboutEx ul li p.pic a") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".content .tabsCon .page a[target]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            7 => function($htmldom){
            },
            8 => function($htmldom) use ($_this){
                //第1种模板拍卖行情栏目下获取分页链接和内容链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".aucList ul li p.pic a") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".aucList .page a[target]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            9 => function($htmldom){
            },
            10 => function($htmldom) use ($_this) {
                //第1种模板资讯栏目下获取分页链接和内容链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".newsList dl dt a") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".newsList .page a[target]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            11 => function($htmldom){
            },
            12 => function($htmldom) use ($_this){
                //第1种模板相册栏目下获取分页链接和内容链接
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".photoList ul li p.pic a") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".page a[target]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            13 => function($htmldom){
            },
            14 => function($htmldom){
            },
            15 => function($htmldom) use ($_this){
                //第1种模板出版著作下获取分页链接和内容
                $href = [];

                $style = $_this->selectType($htmldom);

                if($style == 1){
                    foreach($htmldom->find(".publiceList dl dt a") as $v){
                        $href[] = $v->href;
                    }
                    foreach($htmldom->find(".page a[target]") as $v){
                        $href[] = $v->href;
                    }
                }

                return $href;
            },
            16 => function($htmldom){
            },
            17 => function($htmldom){
                //第2种模板艺术家官网获取栏目链接
                $href = [];

                foreach($htmldom->find("#subNav .level0 ul li a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
            18 => function($htmldom){
            },
            19 => function($htmldom){
                //第2种模板的市场行情列表页获取链接
                $href = [];

                foreach($htmldom->find("tr td a") as $v){
                    $href[] = $v->href;
                }

                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
            20 => function($htmldom){
                //第2种模板作品列表页获取分页链接和内容链接
                $href = [];

                foreach($htmldom->find(".picListUl li .imgWrap a") as $v){
                    $href[] = $v->href;
                }
                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
            21 => function($htmldom){
            },
            22 => function($htmldom){
            },
            23 => function($htmldom){
                //第2种模板资讯列表页获取分页链接和内容链接
                $href = [];

                foreach($htmldom->find(".news li h3 a") as $v){
                    $href[] = $v->href;
                }

                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
            24 => function($htmldom){
            },
            25 => function($htmldom){
                //第2种模板相册首页获取分页链接
                $href = [];

                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
            26 => function($htmldom){
                //第2种模板相册分页获取分页链接
                $href = [];

                foreach($htmldom->find(".listJump a") as $v){
                    $href[] = $v->href;
                }

                return $href;
            },
        ];
        $htmlRule = [
            0 => function($htmldom){
                //艺术家列表页，获取艺术家基本信息
                $user = [];
                foreach($htmldom->find(".shopList") as $k=>$v){
                    $user[$k]["name"] = trim($v->find("h3.fix a.fl")[0]->node->textContent);
                    foreach($v->find("dl.fix dd.mid table tbody tr td p") as $k1=>$v1){
                        $user[$k]["info"][$k1]["attr"] = trim($v1->find("span")[0]->node->textContent);
                        $user[$k]["info"][$k1]["value"] = trim($v1->find("b")[0]->node->textContent);
                    }
                    $user[$k]["only"] = trim($v->find("h3.fix a.fl")[0]->href);
                }

                return ["user" => $user];
            },
            1 => function($htmldom){
            },
            2 => function($htmldom) use ($_this){
                //第1种模板艺术家介绍
                $style = $_this->selectType($htmldom);

                if($style == 1) {
                    //姓名
                    $name = $htmldom->find("#zhcontent h1")[0]->node->textContent;
                    //简介
                    $desc = $htmldom->find("#zhcontent .introInfo .introTxt div")[0]->node->textContent;
                    //头像
                    $headerImg = $htmldom->find("#zhcontent .introInfo .introPic .pic span img")[0]->src;
                    //年表
                    $nianBiao = [];
                    foreach ($htmldom->find("#ZhintroAward table tbody tr") as $k => $v) {
                        $nianBiao[$k]["year"] = $v->find("th")[0]->node->textContent;
                        $nianBiao[$k]["content"] = $v->find("td .txt")[0]->node->textContent;
                    }
                    //获奖情况
                    $huoJiang = [];
                    if ($htmldom->find("#zhhjDetail")) {
                        foreach ($htmldom->find("#zhhjDetail ul li") as $k => $v) {
                            $huoJiang[$k]["year"] = $v->find("em")[0]->node->textContent;
                            $huoJiang[$k]["content"] = $v->find("span")[0]->node->textContent;
                        }
                    }

                    return ["name" => $name, "desc" => $desc, "headerImg" => $headerImg, "nianBiao" => $nianBiao, "huoJiang" => $huoJiang];
                } elseif($style == 4){
                    //头像
                    $headerImg = $htmldom->find(".mainCon .about p img")[0]->src;
                    //简介
                    $desc = trim($htmldom->find(".mainCon .about p")[1]->node->textContent);
                    //年表
                    $nianBiao = [];
                    foreach($htmldom->find(".mainCon .year dl") as $k=>$v){
                        $nianBiao[$k]["year"] = $v->find("dt")[0]->node->textContent;
                        $nianBiao[$k]["content"] = $v->find("dd")[0]->node->textContent;
                    }
                    //获奖情况
                    $huoJiang = [];
                    if($htmldom->find(".mainCon .award")){
                        foreach($htmldom->find(".mainCon .award .awardList li") as $k=>$v){
                            $huoJiang[$k]["year"] = $v->find("span")[0]->node->textContent;
                            $huoJiang[$k]["content"] = $v->node->textContent;
                        }
                    }

                    return ["headerImg" => $headerImg, "desc" => $desc, "nianBiao" => $nianBiao, "huoJiang" => $huoJiang];
                }
            },
            3 => function($htmldom){
            },
            4 => function($htmldom){
            },
            5 => function($htmldom) use ($_this){
                //第1种模板作品详情页
                $style = $_this->selectType($htmldom);

                if($style == 1){
                    //作品名称
                    $title = $htmldom->find(".auctTop .title h1")[0]->node->textContent;
                    //作品图片
                    $thumb = $htmldom->find("#smallPic")[0]->src;
                    //作品信息
                    $info = [];
                    foreach($htmldom->find(".aucInfo .auctMain table tbody tr th") as $k=>$v){
                        $info[$k]["arrt"] = $v->node->textContent;
                    }
                    foreach($htmldom->find(".aucInfo .auctMain table tbody tr td.col") as $k=>$v){
                        $info[$k]["value"] = $v->node->textContent;
                    }

                    return ["title" => $title, "thumb" => $thumb, "info" => $info];
                }
            },
            6 => function($htmldom){
            },
            7 => function($htmldom){
                //展览详情页
                //展览图片
                $imgs = [];
                foreach($htmldom->find("#imgnav #img ul li .imgshow div p img") as $k=>$v){
                    $imgs[$k]["img"] = $v->src;
                }
                foreach($htmldom->find("#imgnav #img ul li span") as $k=>$v){
                    $imgs[$k]["title"] = trim($v->node->textContent);
                }
                //展览信息
                $info = [];
                foreach($htmldom->find(".exInfo .fix dt") as $k=>$v){
                    $info[$k]["attr"] = trim($v->node->textContent);
                }
                foreach($htmldom->find(".exInfo .fix dd") as $k=>$v){
                    $info[$k]["value"] = trim($v->node->textContent);
                }
                //展览介绍
                $desc = $htmldom->find(".exTxt .exText")[0]->node->textContent;

                return ["imgs" => $imgs, "info" => $info, "desc" => $desc];
            },
            8 => function($htmldom){
            },
            9 => function($htmldom){
                //拍卖详情页
                //标题
                $title = $htmldom->find(".titLeft h1")[0]->node->textContent;
                //图片
                $img = $htmldom->find(".page_body .baseTop .auctTop .imgShow .imgbody .imgCont .picSmallList li.curr .smallCon span.smallCell img")[0]->getAttr("data-big");
                //拍卖信息
                $info = [];
                foreach($htmldom->find("table tbody tr th") as $k=>$v){
                    $info[$k]["attr"] = trim($v->node->textContent);
                }
                foreach($htmldom->find("table tbody tr td") as $k=>$v){
                    $info[$k]["value"] = trim($v->node->textContent);
                }

                return ["title" => $title, "img" => $img, "info" => $info];
            },
            10 => function($htmldom){
            },
            11 => function($htmldom) use ($_this) {
                //第1种模板资讯详情页
                $style = $_this->selectType($htmldom);

                if($style == 1) {
                    //标题
                    $title = trim($htmldom->find("h1.title")[0]->node->textContent);
                    //内容
                    $content = trim($htmldom->find(".newsCont .detail")[0]->innertext);

                    return ["title" => $title, "content" => $content];
                }
            },
            12 => function($htmldom){
            },
            13 => function($htmldom){
                //相册详情页
                $info = [];
                foreach($htmldom->find("#img ul li .imgshow div em img") as $k=>$v){
                    $info[$k]["img"] = $v->src;
                }
                foreach($htmldom->find("#text ul li") as $k=>$v){
                    foreach($v->find("p.title strong") as $k1=>$v1){
                        $info[$k]["info"][$k1]["attr"] = $v1->node->textContent;
                    }
                    foreach($v->find("p.title span") as $k1=>$v1){
                        $info[$k]["info"][$k1]["value"] = $v1->node->textContent;
                    }
                }

                return ["info" => $info];
            },
            14 => function($htmldom){
                //第1种模板收藏捐赠列表页
                //本人收藏
                $benRenShouCang = [];
                if($htmldom->find("#myrecordid table tr td")){
                    foreach($htmldom->find("#myrecordid table tr td") as $k=>$v){
                        $benRenShouCang[] = $v->node->textContent;
                    }
                }
                //机构/个人收藏
                $geRenShouCang = [];
                if($htmldom->find("#personrecordid table tr td")){
                    foreach($htmldom->find("#personrecordid table tr td") as $k=>$v){
                        $geRenShouCang[$k]["content"] = $v->node->textContent;
                    }
                    foreach($htmldom->find("#personrecordid table tr th") as $k=>$v){
                        $geRenShouCang[$k]["year"] = $v->node->textContent;
                    }
                }
                //捐赠作品
                $juanZengZuoPin = [];
                if($htmldom->find("#wrokrecordid table tr td")){
                    foreach($htmldom->find("#wrokrecordid table tr td") as $k=>$v){
                        $juanZengZuoPin[] = $v->node->textContent;
                    }
                }

                return ["benRenShouCang" => $benRenShouCang, "geRenShouCang" => $geRenShouCang, "juanZengZuoPin" => $juanZengZuoPin];
            },
            15 => function($htmldom){
            },
            16 => function($htmldom) use ($_this){
                //第1种模板出版著作详情页
                $style = $_this->selectType($htmldom);

                if($style == 1){
                    //图片
                    $img = $htmldom->find(".left .publiceInfo dl dt img")[0]->src;
                    //标题
                    $title = $htmldom->find(".left .publiceInfo dl dd.left h3")[0]->node->textContent;
                    //属性
                    $info = [];
                    foreach($htmldom->find(".left .publiceInfo dl dd.left p") as $v){
                        $info[] = trim($v->node->textContent);
                    }
                    //内容摘要
                    $desc = "";
                    if($htmldom->find(".publiceDetail #Abstractall")){
                        $desc = trim($htmldom->find(".publiceDetail #Abstractall")[0]->node->textContent);
                    }

                    return ["img" => $img, "title" => $title, "info" => $info, "desc" => $desc];
                }
            },
            17 => function($htmldom){
            },
            18 => function($htmldom){
                //第2种模板艺术简历栏目
                //头像图片
                $hraderimg = $htmldom->find(".aboutInfo .pic span img")[0]->src;

                //名字
                $name = trim($htmldom->find(".aboutInfo dd h3")[0]->node->textContent);

                //艺术家信息
                $info = [];
                foreach($htmldom->find(".aboutInfo dd .memTxt p strong") as $k=>$v){
                    $info[$k]["attr"] = trim($v->node->textContent);
                }
                foreach($htmldom->find(".aboutInfo dd .memTxt p span") as $k=>$v){
                    $info[$k]["value"] = trim($v->node->textContent);
                }

                //个人简介
                $desc = $htmldom->find("#resume_detail")[0]->node->textContent;

                //年表
                $yearinfo = [];
                if(empty($htmldom->find(".year .noInfo"))) {
                    foreach ($htmldom->find(".year .yearBox table tbody tr th") as $k => $v) {
                        $yearinfo[$k]["year"] = trim($v->node->textContent);
                    }
                    foreach ($htmldom->find(".year .yearBox table tbody tr td") as $k => $v) {
                        $yearinfo[$k]["content"] = trim($v->node->textContent);
                    }
                }

                //合作机构
                $heZuoJiGou = [];
                if(empty($htmldom->find(".galList .noInfo"))){
                    foreach($htmldom->find(".galList dl") as $k=>$v){
                        $heZuoJiGou[$k]["pic"] = trim($v->find(".pic a img")[0]->src);
                        $heZuoJiGou[$k]["title"] = trim($v->find("dd h4")[0]->node->textContent);
                        $heZuoJiGou[$k]["city"] = trim($v->find("dd p span")[0]->node->textContent);
                        $heZuoJiGou[$k]["year"] = trim($v->find("dd p span")[1]->node->textContent);
                        $heZuoJiGou[$k]["people"] = trim($v->find("dd p span")[2]->node->textContent);
                    }
                }

                //参展经历
                $canZhan = [];
                if(empty($htmldom->find(".aboutTxt")[0]->find(".noInfo"))) {
                    foreach ($htmldom->find(".aboutTxt")[0]->find("ul li strong") as $k => $v) {
                        $canZhan[$k]["year"] = trim($v->node->textContent);
                    }
                    foreach ($htmldom->find(".aboutTxt")[0]->find("ul li span") as $k => $v) {
                        $canZhan[$k]["content"] = trim($v->node->textContent);
                    }
                }

                //获奖收藏
                $shouCang = [];
                if(empty($htmldom->find(".aboutTxt")[1]->find(".noInfo"))){
                    foreach($htmldom->find(".aboutTxt")[1]->find("ul li") as $k=>$v){
                        $shouCang[$k]["year"] = trim($v->find("strong")[0]->node->textContent);
                        $shouCang[$k]["content"] = trim($v->find("span")[0]->node->textContent);
                    }
                }

                //出版经历
                $chuBan = [];
                if(empty($htmldom->find(".publish .noInfo"))){
                    foreach($htmldom->find(".publish dl") as $k=>$v){
                        $chuBan[$k]["pic"] = trim($v->find("dt span img")[0]->src);
                        $chuBan[$k]["title"] = trim($v->find("dd h3")[0]->node->textContent);
                        $chuBan[$k]["chuBanShe"] = trim($v->find("dd p")[0]->node->textContent);
                        $chuBan[$k]["time"] = trim($v->find("dd p")[1]->node->textContent);
                        $chuBan[$k]["tiYao"] = trim($v->find("dd p")[2]->node->textContent);
                    }
                }

                return ["hraderimg"=>$hraderimg, "name"=>$name, "info"=>$info, "desc"=>$desc, "yearinfo"=>$yearinfo, "heZuoJiGou"=>$heZuoJiGou, "canZhan"=>$canZhan, "shouCang"=>$shouCang, "chuBan"=>$chuBan];
            },
            19 => function($htmldom){
            },
            20 => function($htmldom){
            },
            21 => function($htmldom){
                //第2种模板作品详情页
                //标题
                $title = trim($htmldom->find(".proDetail h1")[0]->node->textContent);

                //图片
                $img = trim($htmldom->find("#smallPic")[0]->src);

                //作品信息
                foreach($htmldom->find(".priceNote p") as $k=>$v){
                    $info[$k]["attr"] = trim($v->find("span")[0]->node->textContent);
                    $info[$k]["value"] = trim($v->find("em")[0]->node->textContent);
                }

                return["title" => $title, "img" => $img, "info" => $info];
            },
            22 => function($htmldom){
                //第2种模板参展经历
                $dlDom = $htmldom->find(".unList ul li dl");

                $canzhanData = [];

                if($dlDom) {
                    foreach ($dlDom as $k => $v) {
                        $canzhanData[$k]["img"] = trim($v->find(".pic span img")[0]->src);
                        $canzhanData[$k]["title"] = trim($v->find("dd h4")[0]->node->textContent);

                        //有的p标签中没有内容，所以在这里先判断有没有内容
                        foreach ($v->find("dd p") as $k1 => $v1) {
                            if($v1->find("strong")) {
                                $canzhanData[$k]["info"][$k1]["attr"] = trim($v1->find("strong")[0]->node->textContent);
                                $canzhanData[$k]["info"][$k1]["value"] = trim($v1->find("span")[0]->node->textContent);
                            }
                        }
                    }
                }

                return ["canzhanData" => $canzhanData];
            },
            23 => function($htmldom){
            },
            24 => function($htmldom){
                //第2种模板资讯内容

                //标题
                $title = trim($htmldom->find(".title")[0]->node->textContent);

                //内容
                $content = trim($htmldom->find(".detail")[0]->innertext);

                return ["title"=>$title, "content"=>$content];
            },
            25 => function($htmldom){
            },
            26 => function($htmldom){
                //第2种模板相册获取内容
                $data = [];
                //标题和图片
                foreach($htmldom->find(".shopShow ul li") as $k=>$v){
                    $data[$k]["title"] = trim($v->find("p")[0]->node->textContent);
                    $data[$k]["pic"] = trim($v->find(".pic span img")[0]->getAttr("data-bigsrc"));
                }

                return ["data" => $data];
            },
        ];

        $this->filter->setRule("url",$urlRule);
        $this->filter->setRule("href",$hrefRule);
        $this->filter->setRule("html",$htmlRule);
    }

    //opened钩子，在获取有效数据后执行的钩子
    public function hookOpened($data,$url)
    {
    }

    //voidurl钩子，在获取无效链接后执行的钩子
    public function hookVoidurl($url)
    {
        error_log("VoidUrl:\r\nTime:".date("Y-m-d H:i:s")."\r\nURL:".$url."\r\nRuleIndex:".$this->filter->getRuleIndex()."\r\n",3,BASEDIR."/log/voidurl.log");
    }

    //beforefilter钩子，在确定完规则索引后执行
    public function hookBeforefilter($url)
    {

    }

    //filtered钩子，当爬虫过滤完数据后执行的钩子
    //filteredHref，过滤出来的链接数组
    //filteredHtml，过滤出来的页面内容数组
    public function hookFiltered($filteredData)
    {
//        static $num = 1;
//        error_log("number:".$num."\r\nurl:".$this->crawler->getPresentUrl()."\r\nhref:".json_encode($filteredData["filteredHref"])."\r\nhtml:".json_encode($filteredData["filteredHtml"])."\r\n",3,BASEDIR."/test.log");
//        error_log("number:".$num."\r\nurl:".$this->crawler->getPresentUrl()."\r\nurlqueue:".json_encode(Component::UrlQueue()->getQueue())."\r\n",3,BASEDIR."/test.log");
//        error_log("number:".$num."\r\nurl:".$this->crawler->getPresentUrl()."\r\nRuleIndex:".$this->filter->getRuleIndex()."\r\n",3,BASEDIR."/test.log");
//        $num++;
        print_r($filteredData["filteredHref"]);exit;
    }

    //stop钩子，当爬虫停止前执行的钩子
    public function hookStop()
    {

    }

    //判断模板分类
    private function selectType($htmldom)
    {
        $style = 0;

        if($htmldom->find("div[id=BgDiv]")){
            $style = 1;
        }
        //此规则可判断第4类模板首页
        if($htmldom->find("#indexNavigation")){
            $style = 4;
        }
        //此规则可判断第4类模板栏目页
        if($htmldom->find("div.subNav")){
            $style = 4;
        }

        return $style;
    }
}