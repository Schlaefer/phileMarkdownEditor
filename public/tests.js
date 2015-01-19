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
});