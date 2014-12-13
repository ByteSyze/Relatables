<!DOCTYPE html>
<!--Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Three.js Stuff</title>
		<style>
			body { margin: 0; };
			canvas { width: 100%; height: 100% };
		</style>
	</head>
	<body>
		
		<script src='//cdnjs.cloudflare.com/ajax/libs/three.js/r69/three.min.js'></script>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			var mx=0;
			var my=0;
		
			var scene = new THREE.Scene(); 
			var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 ); 
			var renderer = new THREE.WebGLRenderer(); 
			renderer.setSize( window.innerWidth, window.innerHeight ); 
			document.body.appendChild( renderer.domElement ); 
			var geometry = new THREE.BoxGeometry( 1, 1, 1 ); 
			var material = new THREE.MeshLambertMaterial({ color: 0xdeadbeef }); 
			var cube = new THREE.Mesh( geometry, material ); 
			scene.add( cube ); 
			camera.position.z = 5; 
			
			var light = new THREE.AmbientLight( 0x404040 ); // soft white light 
			scene.add( light );
			
			var light = new THREE.PointLight( 0xff0000, 10, 100 ); 
			light.position.set(1,1,1);
			scene.add( light );
			
			cube.rotation.x += 0.1;
			cube.rotation.y += 0.1;
			
			function render() { 
				cube.rotation.x+=0.01;
				cube.rotation.y+=0.01;
				
				cube.position.set(mx*.01-6,-my*.01+3,0);
				
				
				requestAnimationFrame( render ); 
				renderer.render( scene, camera ); 
			} 
			render();
			
			$("body").mousemove(function(e) {
				mx = e.pageX;
				my = e.pageY;
			})
		</script>
	</body>
</html>