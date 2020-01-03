# 감사와 동행

- [Composer template for Drupal projects](https://github.com/drupal-composer/drupal-project)
- [Drupal Paranoia](https://github.com/drupal-composer/drupal-paranoia)

## 기획 자료들

- 위키: https://github.com/text-consulting-group/gamdonglove/wiki/%ED%99%88
- 목업: https://app.moqups.com/JBF6j8eHpx/view
- 홈페이지 메인화면 변경: https://drive.google.com/drive/u/1/folders/1aYfp91CPvwsJEnJvE_YDNSoUz27lbJ4O
- 스테틱 페이지 원고 변경: https://drive.google.com/drive/u/1/folders/1gm0RsD4mBLXjxD-PWlbQfoCWOL-L3TcO

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

### 콘텐츠타입

1. 공지사항 (article)
2. 자료실( 노드 목록 제목 / 날짜만 노출 / 게시판 형태) / references
3. 활동 (썸네일까지 등록하고, 본문내용도 노출되는 케이스) / actions

### 분류

* 자료실 분류
  - 연간보고서
  - 활동자료
  - 자료모음
* 활동 분류
  - 법률지원
  - 제도개선
  - 공익법 활동

### 화면 url 기준으로 정리

```
## 일반 페이지

- 감동은? /about
- 주요활동 /main-actions
- 찾아오시는 길 /contact
- 함께하는 사람들 /peoeple

## 게시판

공지사항 /notices

자료실 /references

- 연간보고서 /references/annual-report
- 활동자료 /references/action-report
- 자료모음 /references/others

/admin/structure/taxonomy/manage/references_category/overview

활동  /actions

- 법률지원 /actions/legal-support
- 제도개선 /actions/research
- 공익법 활동 /actions/campaign

/admin/structure/taxonomy/manage/actions_category/overview

```

### 뷰 만들기

#### 공지사항 /notices
https://app.moqups.com/JBF6j8eHpx/view/page/aa755995b

- views_notices
  * contents_type: notice
  * field: title, created
  * published: yes

#### 활동  /actions
https://app.moqups.com/JBF6j8eHpx/view/page/ae1b8c99f
분류: 법률지원, 제도개선, 공익법 활동

- views_actions
  + page /actions
    * contents_type: actions
    * field: title, image, body, field_actions, created
    * where: published: yes
    * contextual filter: field_actions
  + block (사이드 메뉴용)
    * contextual filter: field_actions
    * type: summary

#### 자료실  /references
https://app.moqups.com/JBF6j8eHpx/view/page/aa3c65c4a
분류: 연간보고서, 활동자료, 자료모음

- views_references
  + page /references
    * contents_type: references
    * field: title, image, body, field_references, created
    * published: yes
    * contextual filter: field_references
  + block (사이드 메뉴용)
    * contextual filter: field_references
    * type: summary
```
