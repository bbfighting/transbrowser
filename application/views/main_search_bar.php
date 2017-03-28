                <form>
                    <div class="box-body">
                        <div class="form-inline">
                            <div class="form-group">
                                <label for="comment">Species : </label>
                                <!-- <input name="TermTextField" id="query_box" type="text" class="form-control searchbar" autocomplete="off" size="40"> -->
                                <select name="species" class="form-control searchbar">
                                　<option value="hs">human</option>
                                　<option value="mouse">mouse</option>
                                </select>
                                </br>
                                <label for="comment">Official Symbol/Full Name/Alias of a gene : </label>
                                <input name="TermTextField" id="query_box_text" type="text" class="form-control searchbar" size="40" onKeyPress=”return false”>&nbsp;&nbsp;&nbsp;
                                <!-- <button type="button" class="btn-primary" id="query_button">Submit</button> -->
                                (EX : <button type="button" class="btn btn-default btn-xs" id="example1">Zdhhc5</button>)&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn-primary" id="query_button">Submit</button>
                                <button type="reset" class="btn-primary">Reset</button>
                            </div>
                        </div><br>
<!--                         <div class="form-group">
                            <label for="comment">keying in the name you want to search for human genome.&nbsp;Ex : Zdhhc5</label>
                            <p style="white-space: pre;">Choosing the type and keying in the name you want to search for human genome.<br/>For exmaple: Select &quot;official symbol&quot; and type &quot;Jun&quot;, then click Submit button.</p>
                        </div> -->
                    </div><!-- /.box-body -->
                </form>