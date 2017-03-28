                    <table class="table">
                        <tr>
                            <td><strong><?php echo $fullname?> [</strong> <?php echo $tax?> <strong>]</strong><span class="pull-right">GeneID : <a href="http://www.ncbi.nlm.nih.gov/sites/entrez?Db=gene&Cmd=ShowDetailView&TermToSearch=<?php echo $id?>" target="_blank"><?php echo $id?></a></span></td>
                        </tr>
<!--                         <tr>
                            <td valign="bottom" style="font-size:14px">GeneID : <a href="http://www.ncbi.nlm.nih.gov/sites/entrez?" target="_blank">3725</a></td>
                        </tr> -->
                        <tr>
                            <td style="border:1px solid #0000FF;border-left:0;border-right:0">
                                <table class="table" style="width: 95%; margin: auto;">
                                    <tr>
                                        <td colspan="2" style="border-top:0;color:#A86EE6"><i class="fa fa-asterisk"></i><strong> Summary</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid;width:130px;"><div align="right"><strong>Official Symbol</strong></div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $ofname?> &nbsp;<?php echo $graphic?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid;width:130px;"><div align="right"><strong>Full Name</strong></div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $fullname?> &nbsp;</td>
                                    </tr>
<!--                                     <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid;width:120px;"><div align="right">See related</div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $dbxrefs?> &nbsp;</td>
                                    </tr> -->
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid;width:130px;"><div align="right"><strong>Also Known As</strong></div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $alias?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid;width:130px;"><div align="right"><strong>Summary</strong></div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $summary?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><div align="right"><strong>5'UTR</strong></div></td>
                                        <td><?php echo $refseq?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-color:#FFFFFF; border-bottom-width:1px;border-bottom-style: solid; padding:10px">
                                            <div>
                                                <button align="left" type="button" class="btn-primary" onclick="javascript:history.go(-1)">go back</button>                                                   
                                                <!-- <button align="right" type="button" class="btn-primary" id="predictentrez" style="float:right;">IREZonePredT</button> -->
                                            </div>
                                        </td>
                                    </tr>
<!--                                     <tr>
                                        <td colspan="2" style="border-top:0;color:#A86EE6"><i class="fa fa-star"></i><strong> Homologenes</div></td>
                                    </tr>
                                    <tr>
                                        <td><div align="right">HUNAM</div></td>
                                        <td>
                                                        TEST&nbsp;
                                        </td>
                                    </tr> -->
                                </table>
                            </td>
                        </tr>
                    </table>