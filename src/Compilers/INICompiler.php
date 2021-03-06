<?php
namespace extSkel\Compilers;

class INICompiler extends AbstractCompiler
{
    /**
     * Compile INI entries skeleton
     *
     * @param array $options
     * @param array $classInfo
     * @param string $skeleton
     *
     * @return string
     */
    public function compileSkeleton($options, $classInfo, $skeleton)
    {
        if (isset($classInfo['properties']['entries']) === false) {
            throw new \Exception("Entries must declared in INIAnalyzer");
        }
        $entries = $classInfo['properties']['entries'];
        $registerEntries = $unregisterEntries = $displayEntries = '';
        $iniEntries = [];
        if (count($entries) > 0) {
            $registerEntries = 'REGISTER_INI_ENTRIES()';
            $unregisterEntries = 'UNREGISTER_INI_ENTRIES()';
            $displayEntries = 'DISPLAY_INI_ENTRIES()';
            $iniEntries[] = 'PHP_INI_BEGIN()';
            foreach ($entries as $key => $entry) {
                $iniEntries[] = 'PHP_INI_ENTRY("' . $key . '", "' . $entry . '", PHP_INI_ALL, NULL)';
            }
            $iniEntries[] = 'PHP_INI_END()';
        }

        $skeleton = str_ireplace('%register_ini_entries%', $registerEntries, $skeleton);
        $skeleton = str_ireplace('%unregister_ini_entries%', $unregisterEntries, $skeleton);
        $skeleton = str_ireplace('%display_ini_entries%', $displayEntries, $skeleton);
        $skeleton = str_ireplace('%ini_entries%', implode(PHP_EOL, $iniEntries), $skeleton);
        $skeleton = str_ireplace('%zend_function_entry%', 'NULL', $skeleton);
        return $skeleton;
    }
}
