<!DOCTYPE html>
<head>
	<title>Conceptual-Model</title>
</head>
<body>
	<main>
		<h2>Entities and Attributes</h2>
		<p><strong>Profile</strong></p>
		<ul>
		<li>profileID (primary key)</li>
		<li>profileActivationToken</li>
		<li>profileAtHandle</li>
		<li>profileEmail</li>
		<li>profileHash</li>
		<li>profilePhone</li>
		<li>profileSalt</li>
		</ul>
		<p>Product</p>
		<ul>
			<li>productId</li>
			<li>product</li>
		</ul>
		<p><strong>Favorite</strong></p>
	<ul>
		<li>favoriteID (primary key)</li>
		<li>favoriteProfileID (foreign key)</li>
		<li>favoriteDate</li>
</ul>
		<p><strong>Relations</strong></p>
		<ul>
			<li>One <strong>Profile </strong>favorites products - (m to n)</li>
		</ul>
	</main>
</body>