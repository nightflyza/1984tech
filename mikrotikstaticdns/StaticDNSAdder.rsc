:local fileName "";
:local fileContents "";
:local lineArray "";
:local dnsRec "";
:local dnsIP "";
:local dnsTTL "";
:local dnsRecTotal 0;

:foreach tDNsFile in=[/file find name~".1984t"] do={
    :local contentLen;
    :local lineEnd 0;
    :local line "";
    :local lastEnd 0;

    :log warning "";
    :log warning "";
    :log warning ".1984t files found: starting adding process";

    :set fileName [/file get $tDNsFile name];
    :log warning ("Processing file: " . $fileName);

    :set fileContents [/file get $tDNsFile contents];
    :set contentLen [:len $fileContents];

    :while ([:typeof $lineEnd] != "nil" && $lineEnd < $contentLen) do={
        :set lineEnd [:find $fileContents "\n" $lastEnd ];
        :set line [:pick $fileContents $lastEnd $lineEnd];
        :set lastEnd ($lineEnd + 1);

        :if (line != "" && [:len $line] > 0) do={
            :set lineArray [:toarray $line]

            :if ([:typeof $lineArray] = "array" && [:len $lineArray] > 0) do={
                :set dnsRec [:pick $lineArray 0];
                :set dnsIP [:pick $lineArray 1];
                :set dnsTTL [:pick $lineArray 2];

                :log warning ("Adding static DNS record: " . $line);
                /ip dns static add address=$dnsIP name=$dnsRec ttl=$dnsTTL;
                :set dnsRecTotal ($dnsRecTotal + 1);
            } else={
                :log warning "Can not cast DNS record $tmpDNRec to array, skipping"
            }
        } else={
            :log warning "DNS record is empty, skipping"
        }
    }

    :log warning ("Removing " . $fileName);
    /file remove $fileName;
    :log warning "";
    :log warning "";
}

:log warning "Total DNS recs added: $dnsRecTotal";