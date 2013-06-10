<!-- $Id: control_group.xsl 8267 2011-12-11 12:27:18Z sigurdne $ -->
<!-- item  -->
<xsl:template name="control_group" xmlns:php="http://php.net/xsl">

<xsl:call-template name="yui_phpgw_i18n"/>

<div id="details">
			<form action="#" method="post">
				<input type="hidden" name="id" value = "{value_id}">
				</input>
				<dl class="proplist-col">
					<dt>
						<label for="title">Tittel</label>
					</dt>
					<dd>
					<xsl:choose>
						<xsl:when test="editable">
							<input type="text" name="group_name" id="group_name" value="{control_group/group_name}" size="80"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="control_group/group_name"/>
						</xsl:otherwise>
					</xsl:choose>
					</dd>
					<dt>
						<label for="control_area">Kontrollområde</label>
					</dt>
					<dd>
					<xsl:choose>
						<xsl:when test="editable">
							<select id="control_area_id" name="control_area">
								<xsl:apply-templates select="control_area/options"/>
							</select>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="control_group/control_area_name" />
						</xsl:otherwise>
					</xsl:choose>
					</dd>
					<dt>
						<label for="proecdure">Prosedyre</label>
					</dt>
					<dd>
					<xsl:choose>
						<xsl:when test="editable">
							<select id="procedure_id" name="procedure">
								<xsl:apply-templates select="procedure/options"/>
							</select>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="control_group/procedure_name" />
						</xsl:otherwise>
					</xsl:choose>
					</dd>
					<dt>
						<label for="building_part">Bygningsdel</label>
					</dt>
					<dd>
					<xsl:choose>
						<xsl:when test="editable">
							<select id="building_part" name="building_part">
								<xsl:apply-templates select="building_part/building_part_options"/>
							</select>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="control_group/building_part_id" /> - <xsl:value-of select="control_group/building_part_descr" />
						</xsl:otherwise>
					</xsl:choose>
					</dd>
					<xsl:choose>
						<xsl:when test="editable">
							<dt>
								<label><xsl:value-of select="php:function('lang', 'Entity')" /></label>
							</dt>
							<dd>
								<select name="entity_id" id="entity_id">
									<xsl:for-each select="entities">
										<option value="{id}">
											<xsl:if test="selected">
												<xsl:attribute name="selected" value="selected" />
											</xsl:if>
											<xsl:value-of select="name"/>
										</option>
									</xsl:for-each>
								</select>
							</dd>
							<dt>
								<label><xsl:value-of select="php:function('lang', 'Category')" /></label>
							</dt>
							<dd>
								<select name="category_id" id="category_id">
									<xsl:for-each select="categories">
										<option value="{id}">
											<xsl:if test="selected">
												<xsl:attribute name="selected" value="selected" />
											</xsl:if>
											<xsl:value-of select="name"/>
										</option>
									</xsl:for-each>
								</select>
							</dd>
						</xsl:when>
						<xsl:otherwise>
							<dt>
								<label><xsl:value-of select="php:function('lang', 'Entity')" /></label>
							</dt>
							<dd>
								<xsl:value-of select="entity/name" />
							</dd>
							<dt>
								<label><xsl:value-of select="php:function('lang', 'Category')" /></label>
							</dt>
							<dd>
								<xsl:value-of select="category/name" />
							</dd>
						</xsl:otherwise>
					</xsl:choose>
				</dl>

				<div class="form-buttons">
					<xsl:choose>
						<xsl:when test="editable">
							<xsl:variable name="lang_save"><xsl:value-of select="php:function('lang', 'save')" /></xsl:variable>
							<xsl:variable name="lang_cancel"><xsl:value-of select="php:function('lang', 'cancel')" /></xsl:variable>
							<input type="submit" name="save_control_group" value="{$lang_save}" title = "{$lang_save}" />
							<input type="submit" name="cancel_control_group" value="{$lang_cancel}" title = "{$lang_cancel}" />
						</xsl:when>
						<xsl:otherwise>
							<xsl:variable name="lang_edit"><xsl:value-of select="php:function('lang', 'edit')" /></xsl:variable>
							<input type="submit" name="edit_control_group" value="{$lang_edit}" title = "{$lang_edit}" />
						</xsl:otherwise>
					</xsl:choose>
				</div>
				
			</form>
	</div>
</xsl:template>
	
<xsl:template match="options">
	<option value="{id}">
		<xsl:if test="selected != 0">
			<xsl:attribute name="selected" value="selected" />
		</xsl:if>
		<xsl:value-of disable-output-escaping="yes" select="name"/>
	</option>
</xsl:template>

<xsl:template match="building_part_options">
	<option value="{id}">
		<xsl:if test="selected != 0">
			<xsl:attribute name="selected" value="selected" />
		</xsl:if>
		<xsl:value-of select="id"/> - <xsl:value-of disable-output-escaping="yes" select="name"/>
	</option>
</xsl:template>

