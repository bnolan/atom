class User extends Backbone.Model
  getAvatar: ->
    @get('uri') + '/avatar.jpg'
    
class Post extends Backbone.Model
  urlRoot : '/api/post'

  getUser: ->
    @user ||= new User(@get('user'))

  escapeAndLinkifyContent: ->
    linkify(@get('content').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'))
    
class PostCollection extends Backbone.Collection
  model: Post
  url : '/api/newsfeed'
  
  # fetch: ->
  #   $.ajax {
  #     url : '/index.php'
  #     success : ->
  #       
  #   }
  #   

class Newsfeed extends Backbone.View
  initialize: ->
    @template = _.template($("#newsfeed-template").html().sub(/^[\n\s]+/,''))
    @itemTemplate = _.template($("#newsfeed-item-template").html().sub(/^[\n\s]+/,''))
    @collection.fetch {
      success : @render
    }
    
  render: =>
    ul = @$el.empty().html(@template()).find('ul')
    
    @collection.each (model) =>
      $(@itemTemplate { model : model }).appendTo ul

class PostView extends Backbone.View
  initialize: ->
    @template = _.template($("#post-view-template").html().sub(/^[\n\s]+/,''))
    @model.fetch {
      success : @render
    }
    
  render: =>
    @$el.empty().html(@template { model : @model })
    @$el.embedly { 
      method : 'after'
      maxWidth : $(window).width() - 20
    }
    
class NewPostView extends Backbone.View
  initialize: ->
    @template = _.template($("#post-new-template").html().sub(/^[\n\s]+/,''))
    @render()
    
  events: {
    'submit form' : 'onSubmit'
  }
  
  render: =>
    @$el.empty().html(@template { model : @model })
    @delegateEvents()
    
  onSubmit: (e) =>
    post = new Post { content : @$('textarea').val() }
    post.save {}, {
      error : ->
        alert "Error saving your post, please try again..."
        
      success : ->
        window.location.hash = "#"
    }
    
    e.preventDefault()
    

class Router extends Backbone.Router
  routes : {
    '' : 'newsfeed'
    'posts/:id' : 'post'
    'new' : 'newPost'
  }
  
  newsfeed : ->
    view = new Newsfeed {
      collection : new PostCollection
      el : $("body")
    }
    
  post : (id) ->
    view = new PostView {
      model : new Post { id : id }
      el : $("body")
    }
    
  newPost : ->
    view = new NewPostView {
      model : app.owner
      el : $("body")
    }
    

class App    
  constructor: ->
    new Router
    @owner = new User {"name":"Ben Nolan","uri":"http:\/\/atom.localhost\/"}
    
  start: ->
    Backbone.history.start()    
  
$(document).ready ->
  window.app = new App
  app.start()
