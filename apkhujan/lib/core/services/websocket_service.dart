import 'package:dart_pusher_channels/dart_pusher_channels.dart';
import 'dart:developer' as developer;
import 'dart:async';

class PusherEvent {
  final String channelName;
  final String eventName;
  final dynamic data;
  final String? userId;

  PusherEvent({
    required this.channelName,
    required this.eventName,
    this.data,
    this.userId,
  });

  @override
  String toString() =>
      '{ channelName: $channelName, eventName: $eventName, data: $data, userId: $userId }';
}

class WebsocketService {
  static final WebsocketService _instance = WebsocketService._internal();
  factory WebsocketService() => _instance;
  WebsocketService._internal();

  PusherChannelsClient? _client;
  final List<Function(PusherEvent)> _listeners = [];
  final Map<String, StreamSubscription> _subscriptions = {};

  Future<void> init({required String host, required String key}) async {
    developer.log('Initializing Pusher with host: $host');
    
    try {
      final options = PusherChannelsOptions.fromHost(
        scheme: 'ws',
        host: host,
        port: 6001,
        key: key,
      );

      _client = PusherChannelsClient.websocket(
        options: options,
        connectionErrorHandler: (exception, trace, refresh) {
          developer.log("Pusher Connection Error: $exception");
        },
      );

      _client!.lifecycleStream.listen((state) {
        developer.log("Pusher Connection State: $state");
      });
    } catch (e) {
      developer.log("Pusher initialization error: $e");
    }
  }

  void addListener(Function(PusherEvent) listener) {
    _listeners.add(listener);
  }

  void removeListener(Function(PusherEvent) listener) {
    _listeners.remove(listener);
  }

  Future<void> connect() async {
    developer.log('Connecting to Pusher...');
    _client?.connect();
  }

  Future<void> disconnect() async {
    developer.log('Disconnecting from Pusher...');
    _client?.disconnect();
    for (var sub in _subscriptions.values) {
      sub.cancel();
    }
    _subscriptions.clear();
  }
  
  Future<void> subscribe(String channelName) async {
    if (_client == null) return;
    
    developer.log('Subscribing to channel: $channelName');
    final channel = _client!.publicChannel(channelName);
    channel.subscribe();
    
    // Listen to all events on this channel
    _subscriptions[channelName] = channel.bindToAll().listen((event) {
       final pusherEvent = PusherEvent(
         channelName: channelName,
         eventName: event.name,
         data: event.data,
       );
       developer.log("Pusher Event: ${pusherEvent.eventName} on channel ${pusherEvent.channelName}");
       for (var listener in _listeners) {
         listener(pusherEvent);
       }
    });
  }
}
