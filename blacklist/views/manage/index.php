<?php

?>
<div class="table-responsive" style="padding-top: 10px">
    <table id="company-list" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>公司名称</th>
                                <th>创建人</th>
                                <th>原因</th>
                                <th>日期</th>
                                <th>是否审核</th>
                                <th>是否显示</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in list">
                                <td><input type="checkbox" :value="item.id" @click="doCompanyListId(item.id, $event)" />{{key +1}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.reporter}}</td>
                                <td>空</td>
                                <td>{{item.create_date}}</td>
                                <td>{{item.is_verify}}</td>
                                <td>{{item.is_show}}</td>
                            </tr>
                            
                        </tbody>
                        
</table>                
</div>

