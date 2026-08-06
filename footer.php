    </div>
  </div>
</div>

<nav class="bottom-nav">
  <a class="bn-item <?= ($active ?? '')==='explore'?'active':'' ?>" href="explore.php">
    <span class="bn-icon">🔍</span><span>Explore</span>
  </a>
  <a class="bn-item <?= ($active ?? '')==='flow'?'active':'' ?>" href="flow.php">
    <span class="bn-icon">💬</span><span>Flow</span>
  </a>
  <a class="bn-item bn-plus <?= ($active ?? '')==='posts'?'active':'' ?>" href="posts.php">
    <span class="bn-icon">＋</span>
  </a>
  <a class="bn-item <?= ($active ?? '')==='chats'?'active':'' ?>" href="chats.php">
    <span class="bn-icon">✈️</span><span>Chats</span>
  </a>
  <a class="bn-item <?= ($active ?? '')==='requests'?'active':'' ?>" href="requests.php">
    <span class="bn-icon">🔔</span><span>Istekler</span>
    <?php if (!empty($pendingCount)): ?><span class="bn-badge"><?= $pendingCount ?></span><?php endif; ?>
  </a>
</nav>

</body>
</html>
