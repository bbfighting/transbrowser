                    <table class="table">
                        <tr>
                            <td><strong><?php echo $fullname?> [</strong> Homo sapiens <strong>]</strong><span class="pull-right">GeneID : <a href="http://www.ncbi.nlm.nih.gov/sites/entrez?Db=gene&Cmd=ShowDetailView&TermToSearch=<?php echo $id?>" target="_blank"><?php echo $id?></a></span></td>
                        </tr>
<!--                         <tr>
                            <td valign="bottom" style="font-size:14px">GeneID : <a href="http://www.ncbi.nlm.nih.gov/sites/entrez?" target="_blank">3725</a></td>
                        </tr> -->
                        <tr>
                            <td style="border:1px solid #0000FF;border-left:0;border-right:0">
                                <table class="table" style="width: 95%; margin: auto;">
                                    <tr>
                                        <td colspan="2" style="border-top:0;color:#A86EE6"><i class="fa fa-star"></i><strong> Summary</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><div align="right">Official Symbol</div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $ofname?> &nbsp;<a href="http://www.ncbi.nlm.nih.gov<?php echo $graphic?>" target="_blank">[Graphic]</a></td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><div align="right"> Full Name</div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $fullname?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><div align="right">See related</div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $dbxrefs?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><div align="right">Also known as</div></td>
                                        <td style="border-color:#CCCCCC; border-bottom-width:1px;border-bottom-style: solid"><?php echo $alias?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><div align="right">5'UTR</div></td>
                                        <td><?php echo $refseq?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-color:#FFFFFF; border-bottom-width:1px;border-bottom-style: solid; padding:10px">
                                            <div align="right">
                                                <p>
                                                    <button type="button" class="btn btn-default" id="predictentrez">IREZonePredT</button>
                                                </p>
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