all: clean prepare analyze_se diff_se analyze_pe diff_pe analyze_fu diff_fu

clean:
	rm -rf *.log single_end paired_end paired_end_fusion

prepare:
	mkdir -p single_end
	ln -s ../analyze_rna_in1.fastq.gz single_end/SE_L001_R1_001.fastq.gz
	ln -s ../analyze_rna_in2.fastq.gz single_end/SE_L002_R1_001.fastq.gz
	
	mkdir -p paired_end
	ln -s ../analyze_rna_in1.fastq.gz paired_end/PE_L001_R1_001.fastq.gz
	ln -s ../analyze_rna_in2.fastq.gz paired_end/PE_L002_R1_001.fastq.gz
	ln -s ../analyze_rna_in3.fastq.gz paired_end/PE_L001_R2_001.fastq.gz
	ln -s ../analyze_rna_in4.fastq.gz paired_end/PE_L002_R2_001.fastq.gz

	mkdir -p paired_end_fusion
	ln -s ../analyze_rna_in5.fastq.gz paired_end_fusion/FU_L001_R1_001.fastq.gz 
	ln -s ../analyze_rna_in6.fastq.gz paired_end_fusion/FU_L001_R2_001.fastq.gz

analyze_se:
	php ../../src/Pipelines/analyze_rna.php \
			-folder single_end -name SE \
			-steps ma,rc,an -system system.ini \
			-library_type unstranded \
			--log analyze_rna_out_se.log

diff_se:
	diff analyze_rna_out_se.tsv single_end/SE_counts.tsv

analyze_pe:
	php ../../src/Pipelines/analyze_rna.php \
			-folder paired_end -name PE \
			-steps ma,rc,an,fu -system system.ini \
			-library_type unstranded \
			--log analyze_rna_out_pe.log

diff_pe:
	diff analyze_rna_out_pe.tsv paired_end/PE_counts.tsv
	diff analyze_rna_out_pe_fusion.tsv paired_end/PE_var_fusions.tsv

analyze_fu:
	php ../../src/Pipelines/analyze_rna.php \
			-folder paired_end_fusion -name FU \
			-steps ma,rc,an,fu -system system.ini \
			-library_type reverse \
			--log analyze_rna_out_fu.log
diff_fu:
	diff analyze_rna_out_fu.tsv paired_end_fusion/FU_var_fusions.tsv

#################################### DEBUG TARGETS ####################################
	
fast: clean prepare analyze_pe diff_pe

fusion:
	php ../../src/Pipelines/analyze_rna.php \
	    -folder paired_end -name PE \
	    -steps fu -system system.ini \
	    --log analyze_rna_out_pe.log
