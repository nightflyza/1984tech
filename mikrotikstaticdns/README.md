# Mikrotik static DNS records adding
You can use `cli/mtstaticdnsgen` to generate **CLI** output suitable for copypasting into RouterOS CLI/terminal to add static DNS records of forbidden resources.
Following parameters are supported:
- `--preview` - preview Mikrotik static DNS records **CLI** output to stdout
- `--generate` - generate Mikrotik static DNS records **CLI** output to a file
- `--splitchunks` - split domains list to file chunks with size under 4096 Kb for processing with internal Mikrotik script
- `--help` - show small help

Also here lies `StaticDNSAdder.rsc` script which is attended to help you to automatically add static DNS records to your Mikrotik instance. 
Just in case if you're experiencing some troubles with copypasting of the intended **CLI** output into RouterOS CLI/terminal or in case of any other inconveniences of the first method. \
First you need to put this script into your RouterOS `/scripts`. \
Then you need to put specially prepared files with domains list into your RouterOS `/files`.  
The script is supposed to work with `.1984t` files, which you can generate with the help of `cli/mtstaticdnsgen` in the following way:
- go to your `1984tech-master` directory and run   
         
      php cli/mtstaticdnsgen --splitchunks

After placing `StaticDNSAdder.rsc` and chunk files all to their place you just need to run the script. After processing each chunk file will be automatically removed.
      
#### By default:
- Mikrotik static DNS records **CLI** output file will be placed into `/tmp/mt_dnsstatic_script`, as defined with `MT_DNSSTATIC_SCRIPT_PATH` option in `1984tech.ini` 
- Chunk files will be placed into `/tmp`, as defined with `MT_DNSSTATIC_CHUNKS_PATH` option in `1984tech.ini` and named like `mt_dnsstatic_chunk_<number>.1984t`. 
  The names of the chunk files could not be changed, only the placement path.

You can override the default behaviour with `MT_DNSSTATIC_*` options in `1984tech.ini`