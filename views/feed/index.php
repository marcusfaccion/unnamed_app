<?php
use yii\bootstrap\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>

<h1>Index Feed!<?=Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s')?></h1>

 <?php
                      echo AutoComplete::widget([
                            'id' => 'ac-user-friend',
                            'name' => 'ac-user-friend',
                            'clientOptions' => [
                                'source' => new JsExpression('
                                            function (request, response) {
                                                $.ajax({
                                                     url: "users/friends-search",
                                                     type: "GET",
                                                     data: request,
                                                     success: function (data) {
                                                         response($.map(JSON.parse(data), function (desc, val) {
                                                             return {
                                                                 label: desc.label,
                                                                 value: val.value
                                                             };
                                                          }));}
                                                });
                                            }
                                        '),
                                    'open' => new JsExpression('
                                                function( event, ui ) {
                                                    console.log(ui);
                                                }
                                            '),
                                'minLength'=> 2,
                                'delay'=> 100
                            ],
                        ]);
                  ?>