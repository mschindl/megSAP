<?php
require_once("framework.php");

$name = "vc_clincnv_germline";
$ngsbits = get_path("ngs-bits");

start_test($name);
//test germline
//prepare input data_folder
$tmp_folder = output_folder()."/vc_clincnv_germline_data/";
exec2("rm -rf $tmp_folder/*");
exec2("tar xzf ".data_folder()."{$name}_data.tgz -C ".output_folder());
$bed = $tmp_folder."/target_region.bed";
$cov_folder = $tmp_folder."/coverage/";
//test
$out_file1 = output_folder().$name."_out1.tsv";
$log_file1 = output_folder().$name."_out1.log";
check_exec("php ".src_folder()."/NGS/{$name}.php -cov {$cov_folder}/DX000018_02.cov -cov_folder {$cov_folder} -cov_min 20 -bed {$bed} -out {$out_file1} --log {$log_file1}");
check_file($out_file1, data_folder().$name."_out1.tsv");
check_file(substr($out_file1,0,-4).".seg", data_folder().$name."_out1.seg");
check_file(substr($out_file1,0,-4)."_cnvs.seg",data_folder().$name."_out2.seg");

//test germline for panel (sample is supposed to be remission, but contains 1230 exon deletion chr6:28478466-33424368)
//prepare input data_folder
$cov_folder = output_folder()."/vc_clincnv_germline_panel_data/";
exec2("mkdir -p $cov_folder");
exec2("rm -rf $cov_folder/*");
exec2("tar -m -xzf ".data_folder()."{$name}_panel_data.tar.gz -C ".output_folder());
$bed = $cov_folder."/ssHAEv5_2016_07_11_annotated.bed";
//test
$out_file2 = output_folder().$name."_panel_out1.tsv";
$log_file2 = output_folder().$name."_panel_out1.log";
check_exec("php ".src_folder()."/NGS/{$name}.php -cov {$cov_folder}/GS140794_02.cov -cov_folder {$cov_folder} -cov_min 20 -max_cnvs 200 -bed {$bed} -out {$out_file2} --log {$log_file2}");
check_file($out_file2, data_folder().$name."_panel_out1.tsv");
check_file(substr($out_file2,0,-4).".seg", data_folder().$name."_panel_out1.seg");
check_file(substr($out_file2,0,-4)."_cnvs.seg",data_folder().$name."_panel_out2.seg");

//test clincnv for somatic single sample (vc_clincnv_germline is used in this case)
//prepare input data_folder
$tmp_folder = output_folder()."/vc_clincnv_tumor_data/";
exec2("mkdir -p $tmp_folder");
exec2("rm -rf $tmp_folder/*");
exec2("tar xzf ".data_folder()."vc_clincnv_somatic_files.tar.gz -C {$tmp_folder}");
$bed_in = $tmp_folder."/target_region.bed";
$bed = $tmp_folder."/target_region_annotated.bed";
$pipeline = [
        ["{$ngsbits}BedAnnotateGC", "-in {$bed_in} -ref /mnt/share/data/genomes/GRCh37.fa"],
        ["{$ngsbits}BedAnnotateGenes", "-out {$bed}"],
    ];
$parser = new ToolBase("tool_test_vc_clincnv_germline", "Pipeline for Bed Annotation.");
$parser->execPipeline($pipeline, "creating annotated BED file for ClinCNV");
$cov_folder = $tmp_folder."/cov-tumor/";
//test
$out_file3 = output_folder().$name."_tumor_out1.tsv";
$log_file3 = output_folder().$name."_tumor_out1.log";
check_exec("php ".src_folder()."/NGS/{$name}.php -cov {$cov_folder}/DX000015_01.cov -cov_folder {$cov_folder} -cov_min 20 -max_cnvs 200 -bed {$bed} -out {$out_file3} --log {$log_file3} -mosaicism");
check_file($out_file3, data_folder().$name."_tumor_out1.tsv");
check_file(substr($out_file3,0,-4).".seg", data_folder().$name."_tumor_out1.seg");
check_file(substr($out_file3,0,-4)."_cnvs.seg",data_folder().$name."_tumor_out2.seg");

end_test();

?>
