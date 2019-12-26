# 감사와 동행

- [Composer template for Drupal projects](https://github.com/drupal-composer/drupal-project)
- [Drupal Paranoia](https://github.com/drupal-composer/drupal-paranoia)

## development

스테이징 데이터베이스 서버를 사용하도록 전환하면서 개발 설정이 변경됐습니다.

```bash
$ git clone git@github.com:team-durumi/gamdonglove.git
$ cd gamdonglove
$ composer install
$ cp settings.php app/sites/default/
$ cp custom.tar.gz app/themes/custom/gd9
$ tar zxvf app/themes/custom/gd9/custom.tar.gz
$ rm app/themes/custom/gd9/custom.tar.gz
$ composer drupal:paranoia
$ drush rs /
```

## Site-Building

- 감사와 동행 메인 메뉴 :  http://gamdonglove.durumi.io/admin/structure/menu/manage/main
- 활동부분의 2뎁스 메뉴를 1뎁스로 메인에 노출하도록 결정했습니다.
- 콘텐츠타입 1- 공지사항, 자료실 메뉴 ( 노드 목록 제목 / 날짜만 노출 / 게시판 형태)
- 콘텐츠타입 2- 활동 (썸네일까지 등록하고, 본문내용도 노출되는 케이스)