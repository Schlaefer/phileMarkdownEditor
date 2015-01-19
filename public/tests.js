describe('app', function() {

  describe('editor', function() {
    describe('view', function() {
      var view;
      beforeEach(function() {
        var fixture = setFixtures('<div id="#editor"></div>');
        var model = new EditorModel;
        view = new EditorView({el: fixture, config: [], model: model})
      });
      it('has an initialized editor after startup', function() {
        view.triggerMethod('dom:refresh');
        expect(view.editor).not.toBeNull();
      });
      it('is not showing an empty editor document after startup', function() {
        view.triggerMethod('dom:refresh');
        expect(view.$el).not.toContainElement('iframe');
      });
    });
  });

  describe('navbar', function() {
    describe('model', function() {
      it('resolves dirname', function() {
        var model = new NavbarPage({ url: 'foo/bar' })
        var dirname = model.get('dirname');
        expect(dirname).toEqual('foo')
      });
      it('resolves root dirname', function() {
        var model = new NavbarPage({  url: 'bar' })
        var dirname = model.get('dirname');
        expect(dirname).toEqual(null)
      })
      it('resolves sub basename', function() {
        var model = new NavbarPage({  url: 'bar/bar' })
        var dirname = model.get('basename');
        expect(dirname).toEqual('bar.md')
      })
      it('resolves root basename', function() {
        var model = new NavbarPage({  url: 'bar' })
        var dirname = model.get('basename');
        expect(dirname).toEqual('bar.md')
      })
    });

    describe('collection', function() {
      it('has sort order', function() {
        var collection = new NavbarPages([
          {url: 'sub/page2'},
          {url: 'sub/aaa/page1'},
          {url: 'sub/page1'},
          {url: 'toot2'},
          {url: 'root1'},
        ]);
        var result = collection.pluck('url');
        var expected = [
          'root1',
          'sub/page1',
          'sub/page2',
          'sub/aaa/page1',
          'toot2'
        ];
        expect(result).toEqual(expected);
      });
    });
  });
});