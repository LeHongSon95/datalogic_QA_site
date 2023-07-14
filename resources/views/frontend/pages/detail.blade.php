

	
	
@extends('layouts.master')
@section('title', 'Home')

@section('addCss')
   <!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
	
	<!-- Font Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="./assets/css/libs/bootstrap.min.css">
	
	<!-- Style CSS -->
	<link rel="stylesheet" href="./assets/css/style.css">
	
		<!-- Style Page CSS -->
		
			<link rel="stylesheet" href="./assets/css/pages/detail.css">
@endsection

@section('content')
	<title>DataLogic - Q&A Detail</title>
<div class="sticky-footer">
	<div class="site-qa-detail site-page">
		<div class="container">
			<div class="breadcrumb-option d-lg-flex align-items-lg-center justify-content-lg-between">
				<nav aria-label="breadcrumb" class="mb-4 m-lg-0">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="./index.html">Q&A検索</a></li>
						<li class="breadcrumb-item active" aria-current="page">アーチ梁につく小梁を、アーチ梁なりの高さで転ばせたい。</li>
					</ol>
				</nav>
	
				<div class="option-change-font-size d-flex align-items-center ">
					<span class="text me-4">文字サイズ変更</span>
	
					<div class="action-group">
						<button type="button" class="btn btn-change-font-size">大</button>
						<button type="button" class="btn btn-change-font-size">中</button>
						<button type="button" class="btn btn-change-font-size">小</button>
					</div>
				</div>
			</div>
			
			<div class="warp-container">
				<div class="qa-post box-shadow">
					<div class="heading d-md-flex align-items-md-center justify-content-md-between">
						<p class="cate">
							カテゴリ : 本体
						</p>
						
						<p class="date">
							更新日時 : 2023 / 02 / 01
						</p>
					</div>
					
					<h4 class="title">
						アーチ梁につく小梁を、アーチ梁なりの高さで転ばせたい。
					</h4>
					
					<div class="content">
						<p>
							[梁] – [修正]　で梁をクリックします。
							<br>
							修正項目で、転び - 「9 – 開始側梁角度」、梁勾配合わせ - 「4 – 接続先勾配」
							<br>
							を選択し、決定します。
						</p>
						
						<p class="text-center">
							<img src="./images/detail.png" alt="detail">
						</p>
					</div>
					
					<div class="favorite-box text-end">
						<button type="button" class="btn btn-add-favorite d-inline-flex align-items-center">
							<span class="material-icons">star</span>
							<span class="text">お気に入りに追加</span>
						</button>
					</div>
				</div>
				
				<div class="question box-shadow">
					<h4 class="question__title">
						アンケート
					</h4>
					
					<form class="question__form">
						<div class="left">
							<label class="form-label d-none d-lg-block"></label>
							<ul class="list-group list-group-radio reset-list">
								<li class="list-group-item">
									<input id="status-settled"
									       class="form-check-input"
									       value="1"
									       type="radio"
									       name="status"
									>
									<label for="status-settled" class="form-check-label">解決した</label>
								</li>
								
								<li class="list-group-item">
									<input id="status-not"
									       class="form-check-input"
									       value="0"
									       type="radio"
									       name="status"
									>
									<label for="status-not" class="form-check-label">解決しなかった</label>
								</li>
							</ul>
						</div>
						
						<div class="right">
							<label for="comment" class="form-label">ご意見・ご感想をお寄せください。</label>
							
							<textarea id="comment" class="comment form-control" name="comment"></textarea>
							
							<p class="note">送信専用フォームです。お問い合わせにはお答えできません。</p>
							
							<div class="action text-end">
								<button type="submit" class="btn btn-submit-question">
									送信する
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- jquery -->
<script src="./assets/js/libs/jquery.min.js"></script>

<!-- Bootstrap Js -->
<script src="./assets/js/libs/bootstrap.bundle.min.js"></script>

<!-- General Js -->
<script src="./assets/js/pages/general.min.js"></script>
@endsection
