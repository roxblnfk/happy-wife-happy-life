# Central Processor Info

<show-structure for="chapter" depth="2"/>

The CPU info component provides information about the underlying CPU, 
including architecture, number of cores, and other information.

<note>
This component already included in the <code>boson-php/runtime</code>, 
so no separate installation is required when using the runtime.
</note>


## Installation

<tldr>
    <p>
        Via <a href="https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies">Composer</a>:
    </p>
    <p>
        <code lang="bash">composer require boson-php/cpu-info</code>
    </p>
</tldr>

**Requirements:**

* `PHP ^8.4`
* `ext-ffi` (optional, provides more detailed and accurate information about the CPU)
* `ext-com_dotnet` (optional, provides more detailed and accurate information about the CPU)


## Usage

### Basic Information

```php
use Boson\Component\CpuInfo\CentralProcessor;

// Get current CPU information
$cpu = CentralProcessor::createFromGlobals();

// Access basic information
echo 'Architecture: ' . $cpu->arch . "\n";
echo 'Name: ' . $cpu->name . "\n";
echo 'Vendor: ' . ($cpu->vendor ?? '~') . "\n";
echo 'Physical Cores: ' . $cpu->physicalCores . "\n";
echo 'Logical Cores: ' . $cpu->logicalCores . "\n";
echo 'Instruction Sets: ' . implode(', ', $cpu->instructionSets);
```

This code will output something like the following information:

<tabs>
    <tab title="Windows / x86_64">
        <code-block>
        Architecture: amd64
        Name: Intel(R) Core(TM) i7-10700KF CPU @ 3.80GHz
        Vendor: GenuineIntel
        Physical Cores: 8
        Logical Cores: 16
        Instruction Sets: mmx, sse, sse2, sse3, ssse3, sse4.1, sse4.2, fma3, avx, avx2
        </code-block>
    </tab>
    <tab title="Linux / x86_64">
        <code-block>
        Architecture: amd64
        Name: Intel(R) Core(TM) i7-10700KF CPU @ 3.80GHz
        Vendor: GenuineIntel
        Physical Cores: 2
        Logical Cores: 4
        Instruction Sets: mmx, sse, sse2, sse3, ssse3, sse4.1, sse4.2
        </code-block>
        <note>This is an example output from VirtualBox, so the number of cores is 
        different. Also, VirtualBox does not support AVX instruction set, so 
        these instructions are not in the list.</note>
    </tab>
</tabs>

### CPU Architecture

You can get the CPU architecture information from the CPU information 
object (`$cpu->arch`). However, if you do not need all the CPU information, 
it is enough to get the architecture separately using the 
`Architecture::createFromGlobals()` method.

```php
use Boson\Component\CpuInfo\Architecture;

// Get current CPU architecture
$arch = Architecture::createFromGlobals();

// Check for specific architecture
if ($arch === Architecture::Amd64) {
    // Only amd64 (x86_64) specific code
}

// Check for сompatible architectures
if ($arch->is(Architecture::x86)) {
    // x86 and x86-like, for example:
    //  - x86
    //  - amd64 (x86_64)
}
```

The component supports the following architectures:
- `Architecture::x86`
- `Architecture::Amd64`
- `Architecture::Arm`
- `Architecture::Arm64`
- `Architecture::Itanium`
- `Architecture::RiscV32` 
- `Architecture::RiscV64`
- `Architecture::Mips`
- `Architecture::Mips64`
- `Architecture::PowerPc`
- `Architecture::PowerPc64`
- `Architecture::Sparc`
- `Architecture::Sparc64`

### Instruction Sets

<secondary-label ref="macos-limitations"/>

The component provides information about supported CPU instruction sets. 
You can check if a specific instruction set is supported:

```php
use Boson\Component\CpuInfo\CentralProcessor;
use Boson\Component\CpuInfo\InstructionSet;

$cpu = CentralProcessor::createFromGlobals();

// Check if specific instruction set is supported
if ($cpu->isSupports(InstructionSet::AVX2)) {
    // AVX2 specific code
}
```

<tabs>
    <tab title="x86 / amd64 (x86_64)">
        <list>
        <li><code>InstructionSet::MMX</code> - MultiMedia eXtensions</li>
        <li><code>InstructionSet::SSE</code> - Streaming SIMD Extensions</li>
        <li><code>InstructionSet::SSE2</code> - Streaming SIMD Extensions 2</li>
        <li><code>InstructionSet::SSE3</code> - Streaming SIMD Extensions 3</li>
        <li><code>InstructionSet::SSSE3</code> - Supplemental Streaming SIMD Extensions 3</li>
        <li><code>InstructionSet::SSE4_1</code> - Streaming SIMD Extensions 4.1</li>
        <li><code>InstructionSet::SSE4_2</code> - Streaming SIMD Extensions 4.2</li>
        <li><code>InstructionSet::FMA3</code> - Fused Multiply-Add 3</li>
        <li><code>InstructionSet::AVX</code> - Advanced Vector Extensions</li>
        <li><code>InstructionSet::AVX2</code> - Advanced Vector Extensions 2</li>
        <li><code>InstructionSet::AVX512</code> - Advanced Vector Extensions 512</li>
        </list>
    </tab>
    <tab title="arm / arm64 (aarch64)">
        There is currently no support for checking instructions for this platform
    </tab>
</tabs>

<tabs>
<tab title="MacOS">
<warning>
Please note that the instruction set information in macOS may not be 
accurate due to virtualization and testing issues.

Full implementation of macOS support is possible in the future.
</warning>
</tab>
</tabs>