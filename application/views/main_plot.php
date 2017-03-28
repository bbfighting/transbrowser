                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="translation_map">Input Information</a></legend>
                        <table class="table table-bordered table-striped" style="word-break:break-all">
                            <tbody>
                                <tr>
                                    <td style="width:20%;"><strong><?php echo $method_th_name?> Name</strong></td>
                                    <td ><?php echo $GeneName?></td>
                                </tr>
                                <?php echo $method_th_nm?>
                                <tr>
                                    <td style="width:20%;"><strong><?php echo $method_th_lenseq?></strong></td>
                                    <td><div style="width:80%;font-family:'Courier New', Courier, monospace"><?php echo $seq_len?></div></td>
                                </tr>
                                <tr>
                                    <td style="width:20%;"><strong><?php echo $method_th_seq?></strong></td>
                                    <td><div style="width:80%;font-family:'Courier New', Courier, monospace"><?php echo $Sequence?><span style="color: #FF0000"><?php echo $SC?></span></div></td>
                                </tr>
                           </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="translation_map">Output Information</a></legend>
                        <?php echo $outputinfo_table?>
                    </fieldset></br></br></br></br>        
                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="translation_map">Graphic View</a></legend>
                        <div class="far img"><img></div>
                    </fieldset>
                    <form role=\"form\" method=\"post\" enctype=\"multipart/form-data\">
                        <?php echo $ribosome_choose?>
                        <div class="form-inline">
                            <label for="comment">Change Region : </label>
                            <span class="form-group">
                                <input type="text" class="form-control searchbar" name="cor1" maxlength="5" placeholder="<?php echo $cor1;?>" onkeyup="value=value.replace(/[^\d-]/g,'')" style="width: 100px" value="<?php echo $cor1;?>">
                                &nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control searchbar" name="cor2" maxlength="5" placeholder="-1" onkeyup="value=value.replace(/[^\d-]/g,'')" style="width: 100px" value=-1>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span id="errorrange">
                                    <!-- <div class="alert-box error"><span>error: </span>Write your error message here.</div> -->
                                </span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="hidden" name="key" value=<?php echo $imgkey;?>>
                                <input type="hidden" name="species" value=<?php echo $imgspecies;?>>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="validate_region"><i class="fa fa-exchange"></i> Refresh</button>              
                            </span> 
                        </div>    
                    </form>         
                    </br></br></br></br></br>  
                    <fieldset class="beta psi">
                        <legend><a class="anchor" id="Sequence map">Sequence View</a></legend>
                        <div class="farseq img"><img></div>
                    </fieldset></br></br>
                    <div align="left">
                        <button type="button" class="btn-primary" onclick="javascript:history.go(-1)">go back</button>                                                   
                    </div>                    