<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body style="padding-bottom: 70px">        
        <?php $this->beginBody() ?>
        <div class="wrap">
            <!--div class="row">
                <div class="col-md-12"-->
                    <?php
                    //导航栏
                    NavBar::begin([
                        'brandLabel' => '黑名单',
                        'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            'class' => 'navbar-inverse navbar-static-top',
                        ],
                    ]);

                    //判断导航栏应该出现的菜单条目
                    if (Yii::$app->user->isGuest) {
                        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                    } else {
                        $menuItems[] = '<li>'
                            . Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm()
                            . '</li>';
                    }
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'items' => $menuItems,
                    ]);

                    NavBar::end();
                    ?>
                <!--/div>
            </div-->
            <!-- end first row -->
            <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="btn-group" role="toolbar">
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add-company">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;添加
                        </button>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp;修改
                        </button>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp;审核
                        </button>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span>&nbsp;详情
                        </button>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;显示
                        </button>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>&nbsp;隐藏
                        </button>
                    </div>
                    <?= $content ?>
                    <!-- end table-responsive -->
                </div>
                <div class="col-md-1"></div>
            </div>
            <!-- end second row -->
            <nav class="navbar">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 text-center">
                    <nav id="pagination" aria-label="Page navigation">
                        <ul class="pagination">
                          <li>
                            <a href="#" aria-label="Previous">
                              <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                          <li v-bind:class="{active : pagination.currentPage == n}" v-on:click="changePage(n)" v-for="n in pagination.pageCount"><a href="javascript:;">{{ n }}</a></li>
                          <li>
                            <a href="#" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                </div>
                <div class="col-md-1"></div>
            </div>
            <!-- end third row -->
            <footer class="text-center hidden-xs">
            本网站条目由网友提供，本网站不作任何宣导作用
            </footer>
            </nav>
            </div>
            <!-- end row container -->
        </div>
        <!-- end wrap DIV -->
        <!-- Modal createCompany -->
        <div class="modal fade" id="add-company" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">添加条目</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="create-company" role="form">
                    <div class="form-group">
                      <label for="company-name" id="company-name" class="control-label col-md-2 col-xs-3 text-right">公司名称:</label>
                      <div class="col-md-10 col-xs-9">
                        <input type="text" class="form-control" id="company-name" name="name" v-model="company.name">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="nick-name" class="control-label col-md-2 col-xs-3 text-right">昵称:</label>
                      <div class="col-md-10 col-xs-9">
                        <input type="text" class="form-control" id="nick-name" name="nickname" v-model="company.nickname">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="nick-name" class="control-label col-md-2 col-xs-3 text-right">匿名:</label>
                      <label class="radio-inline col-md-4 col-xs-4 col-xs-offset-1">
                        <input type="radio" value="1" class="" id="nick-name" name="anonymous" v-model="company.anonymous">是
                      </label>
                      <label class="radio-inline col-md-4 col-xs-3">
                          <input type="radio" value="0" class="" id="nick-name" name="anonymous" v-model="company.anonymous" checked="">否
                      </label>
                    </div>
                    <div class="form-group">
                        <label id="contact_method" for="contact" class="control-label col-md-2 col-xs-3 text-right">联系方式:</label>
                        <div class="col-md-2 col-xs-3">
                            <select class="form-control-static" v-model="company.contact_method">
                                <option value="QQ" selected="selected">QQ</option>
                                <option value="weixin">微信</option>
                                <option value="phone">电话</option>
                                <option value="email">E-mail</option>
                            </select>
                        </div>
                        <div class="col-md-8 col-xs-6" style="padding-left: 0">
                                <input type="text" class="form-control" id="contact" v-model="company.contact"> 
                        </div>
                            
                    </div>
                    <div class="form-group">
                      <label for="message-text" class="control-label col-md-2">原因:</label>
                      <div class="col-md-10">
                          <textarea class="form-control" id="message-text" rows="6" v-model="company.comment"></textarea>
                      </div>
                    </div>
                </form>
              </div> <!-- end .modal-body -->
              <div class="modal-footer">
                <div class="help-block text-left">请真实的填写您的联系方式，以便联系确认，因为不是社会共识条目，会作无效数据处理（暂时没实现第三方登录）</div>
                <button type="button" class="btn btn-primary" v-on:click="createCompany">提交</button>
              </div>
            </div>
          </div>
        </div> <!-- end modal createCompany -->
        
        <div class="modal fade " id="tip-message" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="closeCreateCompanyModal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">添加条目</h4>
              </div>
              <div class="modal-body">
                  <span class="">{{tipMessage}}</span>
              </div> <!-- end .modal-body -->
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="closeCreateCompanyModal">确认</button>
              </div>
            </div>
          </div>
        </div> <!-- end modal alert -->
        <style>
            #company-name {
                padding-right: 0;
            }
            
            #contact_method {
                padding-right: 0;
            }
        </style>
    <?php $this->endBody() ?>
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script type="text/javascript">
            var tipMessage = new Vue({
                el: "#tip-message",
                data: {
                    tipMessage: "",
                    code: null,
                    returnCode: {   //返回代码的含义
                        success: 0,
                        waring:  1,
                        error:   2,
                    }
                },
                methods: {
                    closeCreateCompanyModal: function() {
                        if(this.code === this.returnCode.success) {
                            createCompany.company.name = "";
                            createCompany.company.nickname = "";
                            createCompany.company.anonymous = 0;
                            createCompany.company.contact_method = "QQ";
                            createCompany.company.contact = "";
                            createCompany.company.comment = "";
                            $("#add-company").modal("hide");
                        }
                    }
                }
            });
            
            var createCompany = new Vue ({
                el: "#add-company",
                data: {
                    company : {
                        name: "",
                        nickname: "",
                        anonymous: 0,   //0代表不匿名， 1代表匿名
                        contact_method: "QQ",   //因为服务端使用的下划线写法
                        contact: "",
                        comment: "",
                    },  
                },
                methods: {
                    createCompany: function() {
                        console.log(JSON.stringify(this.$data.company));
                        $.ajax({
                            type: "POST",
                            url : "<?= Yii::getAlias('@web') ?>/v1/company",
                            dataType: "json",
                            contentType: "application/json; charset=utf-8",
                            data : JSON.stringify(this.$data.company),
                            success: function(data) {
                                this.tipMessage = data.reason;
                                this.code = data.error;
                                $("#tip-message").modal("show");
                                if(data.error == tipMessage.returnCode.success) {
                                    //TODO vmlist.list.push();
                                }
                            }.bind(tipMessage),
                            error: function(data) {
                                console.log(data);
                                console.log(JSON.stringify(this.$data.company));
                            }.bind(this),
                        });
                    },
                }
            });
            
            var pagination = new Vue ({
                el : "#pagination",
                data: {
                    pagination : [],
                    currentPagination : [],
                },
                methods: {
                    changePage : function(page) {
                        if(this.pagination.currentPage == page) {
                            return;
                        }
                        $.ajax({
                            type : "GET",
                            url  : "<?= Yii::getAlias('@web') ?>/v1/company"+"?"+"page="+page,                            
                            success : function(data) {
                                vmlist.list = data.data.companies;
                                pagination.currentPagination = data.data._link;
                                pagination.pagination = data.data._meta;
                            },
                        });
                    },
                },
            });
            
            var vmlist = new Vue({
                el : "#company-list",
                data :{
                    list : [],
                    idArray : [],
                },
                mounted : function() {
                        $.ajax({
                            type : "GET",
                            url  : "<?= Yii::getAlias('@web') ?>/v1/company",                            
                            success : function(data) {
                                this.list = data.data.companies;
                                pagination.currentPagination = data.data._link;
                                pagination.pagination = data.data._meta;
                            }.bind(this),
                        });
                },
                methods : {
                    doCompanyListId : function (id, event) {
                        var tempId = {"id" : id};
                        if(event.target.checked) {
                            this.idArray.push(tempId);
                        } else {
                            for(var i=0; i<this.idArray.length; i++) {
                                if(this.idArray[i].id == tempId.id) {
                                  this.idArray.splice(i, 1);
                                  break;
                                }
                            }
                        }
                    }
                },
            });
        </script>
    </body>
</html>
<?php $this->endPage() ?>
