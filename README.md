# CustomArray
A PHP Class to work better with arrays

## Examples 
### Getting the last item in the array :

	$arr = new CustomArray(['a', 'b', 'c']);
	echo $arr[-1]; // c

### The equivalent of array_shift

	echo $arr['1:']; // b, c
	echo $arr['1:2']; // b, c
	echo $arr[':2']; // a, b
	echo $arr[':-1']; // c, b, a

### Getting the array of elements that matches a user function

	$arr = new CustomArray(['a', 'b', 'foo', 'bar']);
	echo $arr[function ($key, $value) { return strlen($value) === 3; }]; // foo, bar

### Transforming the element of the array with a user function (you can do the same with the keys of the array)

	$arr = new CustomArray(['a', 'b', 'c']);
	echo $arr->valuesTo(function ($val) { return '--'.$val.'--'; }); // --a--, --b--, --c--

### Save a method and use it

	$arr = new CustomArray(['foo', 'bar']);
	$arr->createFunc('allToUpper', function (&$data) {
		for ($i = 0; $i < count($data); $i++) {
			$data[$i] = strtoupper($data[$i]);
		}
	});
	$arr->allToUpper();
	echo $arr[0] // FOO
	echo $arr[1] // BAR
	
	$arr->add('hey');
	echo $arr[2] // hey
	$arr->allToUpper();
	echo $arr[2] // HEY
